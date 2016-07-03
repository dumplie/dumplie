<?php

declare (strict_types = 1);

namespace Dumplie\Test\Integration\Infrastructure\Doctrine\Dbal\Metadata;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Dumplie\Application\Metadata\Schema;
use Dumplie\Application\Metadata\Schema\Field\TextField;
use Dumplie\Application\Metadata\Schema\TypeSchema;
use Dumplie\Infrastructure\Doctrine\Dbal\Metadata\DoctrineStorage;
use Dumplie\Infrastructure\Doctrine\Dbal\Metadata\DoctrineStorageException;
use Dumplie\Infrastructure\Doctrine\Dbal\Metadata\Field\TextMapping;
use Dumplie\Infrastructure\Doctrine\Dbal\Metadata\TypeRegistry;
use Dumplie\Test\Doctrine\DbalTestCase;
use Ramsey\Uuid\Uuid;

class DoctrineStorageTest extends DbalTestCase
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var Schema
     */
    private $schema;

    /**
     * @var DoctrineStorage
     */
    private $storage;

    public function setUp()
    {
        $this->connection = DriverManager::getConnection(
            json_decode(DUMPLIE_TEST_DB_CONNECTION, true)
        );

        $this->createDatabase($this->connection);

        if ($this->connection->getSchemaManager()->tablesExist(DoctrineStorage::TABLE_PREFIX . '_sample_meta')) {
            $this->connection->getSchemaManager()->dropTable(DoctrineStorage::TABLE_PREFIX . '_sample_meta');
        }

        $type = new TypeSchema(
            'meta',
            [
                'id' => new TextField(),
                'text' => new TextField(null, false, ['index' => true])
            ]
        );

        $this->schema = new Schema('sample');
        $this->schema->add($type);

        $this->storage = new DoctrineStorage(
            $this->connection,
            new TypeRegistry(
                [
                    new TextMapping()
                ]
            )
        );
    }

    public function test_create()
    {
        $this->storage->create($this->schema);

        $result = $this->connection
            ->getSchemaManager()
            ->tablesExist(DoctrineStorage::TABLE_PREFIX . '_sample_meta');

        $this->assertTrue($result);
    }

    public function test_create_with_index()
    {
        $this->storage->create($this->schema);

        $result = $this->connection
            ->getSchemaManager()
            ->listTableIndexes(DoctrineStorage::TABLE_PREFIX . '_sample_meta');

        $this->assertCount(2, $result);
    }

    public function test_create_throws_exception_when_trying_to_create_existing_table()
    {
        $this->expectException(DoctrineStorageException::class);
        $this->expectExceptionMessage('Table "metadata_sample_meta" already exists');

        $this->storage->create($this->schema);
        $this->storage->create($this->schema);
    }

    public function test_alter_existing_table()
    {
        $foo = new TypeSchema('meta', ['id' => new TextField(), 'foo' => new TextField()]);
        $fooSchema = new Schema('sample');
        $fooSchema->add($foo);

        $bar = new TypeSchema('meta', ['id' => new TextField(), 'bar' => new TextField()]);
        $barSchema = new Schema('sample');
        $barSchema->add($bar);

        $this->storage->create($fooSchema);
        $this->storage->alter($barSchema);

        $result = $this->connection
            ->getSchemaManager()
            ->listTableColumns(DoctrineStorage::TABLE_PREFIX . '_sample_meta');

        $this->assertArrayHasKey('bar', $result);
    }

    public function test_alter_non_existing_table()
    {
        $this->storage->alter($this->schema);

        $result = $this->connection
            ->getSchemaManager()
            ->listTableColumns(DoctrineStorage::TABLE_PREFIX . '_sample_meta');

        $this->assertArrayHasKey('text', $result);
    }

    public function test_drop()
    {
        $this->storage->create($this->schema);
        $this->storage->drop($this->schema);

        $result = $this->connection->getSchemaManager()->tablesExist(DoctrineStorage::TABLE_PREFIX . '_sample_meta');
        $this->assertFalse($result);
    }

    public function test_find_by_returns_matching_results()
    {
        $uuid = (string) Uuid::uuid4();
        $this->storage->create($this->schema);
        $this->storage->save('sample', 'meta', $uuid, ['text' => 'value']);

        $result = $this->storage->findBy('sample', 'meta', ['id' => $uuid]);

        $this->assertEquals(
            [
                [
                    'id' => $uuid,
                    'text' => 'value'
                ]
            ],
            $result
        );
    }

    public function test_find_by_returns_empty_list_when_no_matches_found()
    {
        $this->storage->create($this->schema);
        $this->storage->save('sample', 'meta', (string) Uuid::uuid4(), ['text' => 'value']);

        $result = $this->storage->findBy('sample', 'meta', ['id' => (string) Uuid::uuid4()]);

        $this->assertEmpty($result);
    }

    public function test_saving_with_insert()
    {
        $uuid = (string) Uuid::uuid4();
        $this->storage->create($this->schema);
        $this->storage->save('sample', 'meta', $uuid, ['text' => 'value']);

        $result = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from(DoctrineStorage::TABLE_PREFIX . '_sample_meta')
            ->where('id = \'' . $uuid . '\'')
            ->execute()
            ->fetch();

        $this->assertEquals(
            [
                'id' => $uuid,
                'text' => 'value'
            ],
            $result
        );
    }

    public function test_saving_with_update()
    {
        $uuid = (string) Uuid::uuid4();

        $this->storage->create($this->schema);
        $this->storage->save('sample', 'meta', $uuid, ['text' => 'old-value']);
        $this->storage->save('sample', 'meta', $uuid, ['text' => 'new-value']);

        $result = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from(DoctrineStorage::TABLE_PREFIX . '_sample_meta')
            ->where('id = :id')
            ->setParameter('id', $uuid)
            ->execute()
            ->fetch();

        $this->assertEquals(
            [
                'id' => $uuid,
                'text' => 'new-value',
            ],
            $result
        );
    }

    public function test_has()
    {
        $uuid = (string) Uuid::uuid4();
        $this->storage->create($this->schema);
        $this->storage->save('sample', 'meta', $uuid, ['text' => 'value']);

        $result = $this->storage->has('sample', 'meta', $uuid);

        $this->assertTrue($result);
    }

    public function test_delete()
    {
        $uuid = (string) Uuid::uuid4();
        $this->storage->create($this->schema);
        $this->storage->save('sample', 'meta', $uuid, ['text' => 'value']);

        $this->storage->delete('sample', 'meta', $uuid);

        $result = $this->storage->has('sample', 'meta', $uuid);

        $this->assertTrue($result);
    }
}
