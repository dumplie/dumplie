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

class DoctrineStorageTest extends \PHPUnit_Framework_TestCase
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
        $this->connection = DriverManager::getConnection(['url' => 'sqlite:///:memory:']);

        if ($this->connection->getSchemaManager()->tablesExist(DoctrineStorage::TABLE_PREFIX . '_sample_meta')) {
            $this->connection->getSchemaManager()->dropTable(DoctrineStorage::TABLE_PREFIX . '_sample_meta');
        }

        $type = new TypeSchema(
            'meta',
            [
                'id' => new TextField(),
                'field' => new TextField(null, false, ['index' => true])
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

        $this->assertArrayHasKey('field', $result);
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
        $this->storage->create($this->schema);
        $this->storage->save('sample', 'meta', 'meta-id', ['field' => 'value']);

        $result = $this->storage->findBy('sample', 'meta', ['id' => 'meta-id']);

        $this->assertEquals(
            [
                [
                    'id' => 'meta-id',
                    'field' => 'value'
                ]
            ],
            $result
        );
    }

    public function test_find_by_returns_empty_list_when_no_matches_found()
    {
        $this->storage->create($this->schema);
        $this->storage->save('sample', 'meta', 'meta-id', ['field' => 'value']);

        $result = $this->storage->findBy('sample', 'meta', ['id' => 'fake-id']);

        $this->assertEmpty($result);
    }

    public function test_saving_with_insert()
    {
        $this->storage->create($this->schema);
        $this->storage->save('sample', 'meta', 'meta-id', ['field' => 'value']);

        $result = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from(DoctrineStorage::TABLE_PREFIX . '_sample_meta')
            ->where('id = \'meta-id\'')
            ->execute()
            ->fetch();

        $this->assertEquals(
            [
                'id' => 'meta-id',
                'field' => 'value'
            ],
            $result
        );
    }

    public function test_saving_with_update()
    {
        $this->storage->create($this->schema);
        $this->storage->save('sample', 'meta', 'meta-id', ['field' => 'old-value']);
        $this->storage->save('sample', 'meta', 'meta-id', ['field' => 'new-value']);

        $result = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from(DoctrineStorage::TABLE_PREFIX . '_sample_meta')
            ->where('id = :id')
            ->setParameter('id', 'meta-id')
            ->execute()
            ->fetch();

        $this->assertEquals(
            [
                'id' => 'meta-id',
                'field' => 'new-value',
            ],
            $result
        );
    }

    public function test_has()
    {
        $this->storage->create($this->schema);
        $this->storage->save('sample', 'meta', 'meta-id', ['field' => 'value']);

        $result = $this->storage->has('sample', 'meta', 'meta-id');

        $this->assertTrue($result);
    }

    public function test_delete()
    {
        $this->storage->create($this->schema);
        $this->storage->save('sample', 'meta', 'meta-id', ['field' => 'value']);

        $this->storage->delete('sample', 'meta', 'meta-id');

        $result = $this->storage->has('sample', 'meta', 'meta-id');

        $this->assertTrue($result);
    }
}
