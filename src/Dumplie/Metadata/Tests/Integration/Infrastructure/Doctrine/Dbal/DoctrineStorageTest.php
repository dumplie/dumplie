<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Tests\Integration\Infrastructure\Doctrine\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Dumplie\Metadata\Schema;
use Dumplie\Metadata\Schema\Field\AssociationField;
use Dumplie\Metadata\Schema\Field\TextField;
use Dumplie\Metadata\Schema\TypeSchema;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\DoctrineStorage;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\DoctrineStorageException;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\TypeRegistry;
use Dumplie\SharedKernel\Tests\Doctrine\DBALHelper;
use Ramsey\Uuid\Uuid;

class DoctrineStorageTest extends \PHPUnit_Framework_TestCase
{
    use DBALHelper;

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

    public static function setUpBeforeClass()
    {
        self::createDatabase();
    }

    public function setUp()
    {
        $this->connection = DriverManager::getConnection(
            json_decode(DUMPLIE_TEST_DB_CONNECTION, true)
        );

        if ($this->connection->getSchemaManager()->tablesExist('metadata_test_bar')) {
            $this->connection->getSchemaManager()->dropTable('metadata_test_bar');
        }

        if ($this->connection->getSchemaManager()->tablesExist('metadata_test_foo')) {
            $this->connection->getSchemaManager()->dropTable('metadata_test_foo');
        }

        $foo = new TypeSchema(
            'foo',
            [
                'id' => new TextField(),
                'text' => new TextField(null, false, ['index' => true])
            ]
        );

        $bar = new TypeSchema(
            'bar',
            [
                'id' => new TextField(),
                'link' => new AssociationField('test', $foo)
            ]
        );

        $this->schema = new Schema('test');
        $this->schema->add($foo);
        $this->schema->add($bar);

        $this->storage = new DoctrineStorage(
            $this->connection,
            TypeRegistry::withDefaultTypes()
        );
    }

    public function test_create()
    {
        $this->storage->create($this->schema);

        $result = $this->connection->getSchemaManager()->tablesExist(['metadata_test_foo', 'metadata_test_bar']);

        $this->assertTrue($result);
    }

    public function test_create_with_index()
    {
        $this->storage->create($this->schema);

        $this->assertCount(2, $this->connection->getSchemaManager()->listTableIndexes('metadata_test_foo'));
        $this->assertCount(2, $this->connection->getSchemaManager()->listTableIndexes('metadata_test_bar'));
    }

    public function test_create_throws_exception_when_trying_to_create_existing_table()
    {
        $this->expectException(DoctrineStorageException::class);
        $this->expectExceptionMessage('Table "metadata_test_foo" already exists');

        $this->storage->create($this->schema);
        $this->storage->create($this->schema);
    }

    public function test_alter_existing_table()
    {
        $foo = new TypeSchema('foo', ['id' => new TextField(), 'foo' => new TextField()]);
        $fooSchema = new Schema('test');
        $fooSchema->add($foo);

        $bar = new TypeSchema('foo', ['id' => new TextField(), 'bar' => new TextField()]);
        $barSchema = new Schema('test');
        $barSchema->add($bar);

        $this->storage->create($fooSchema);
        $this->storage->alter($barSchema);

        $result = $this->connection->getSchemaManager()->listTableColumns('metadata_test_foo');

        $this->assertArrayHasKey('bar', $result);
    }

    public function test_alter_non_existing_table()
    {
        $this->storage->alter($this->schema);

        $result = $this->connection->getSchemaManager()->listTableColumns('metadata_test_foo');

        $this->assertEquals(['id', 'text'], array_keys($result));
    }

    public function test_drop()
    {
        $this->storage->create($this->schema);
        $this->storage->drop($this->schema);

        $result = $this->connection->getSchemaManager()->tablesExist(['metadata_test_foo', 'metadata_test_bar']);

        $this->assertFalse($result);
    }

    public function test_find_by_returns_matching_results()
    {
        $uuid = (string) Uuid::uuid4();
        $this->storage->create($this->schema);
        $this->storage->save('test', 'foo', $uuid, ['text' => 'value']);

        $result = $this->storage->findBy('test', 'foo', ['id' => $uuid]);

        $this->assertEquals(
            [
                'id' => $uuid,
                'text' => 'value'
            ],
            $result
        );
    }

    public function test_find_by_returns_empty_list_when_no_matches_found()
    {
        $this->storage->create($this->schema);
        $this->storage->save('test', 'foo', (string) Uuid::uuid4(), ['text' => 'value']);

        $result = $this->storage->findBy('test', 'foo', ['id' => (string) Uuid::uuid4()]);

        $this->assertEmpty($result);
    }

    public function test_saving_with_insert()
    {
        $uuid = (string) Uuid::uuid4();
        $this->storage->create($this->schema);
        $this->storage->save('test', 'foo', $uuid, ['text' => 'value']);

        $result = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('metadata_test_foo')
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
        $this->storage->save('test', 'foo', $uuid, ['text' => 'old-value']);
        $this->storage->save('test', 'foo', $uuid, ['text' => 'new-value']);

        $result = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('metadata_test_foo')
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
        $this->storage->save('test', 'foo', $uuid, ['text' => 'value']);

        $result = $this->storage->has('test', 'foo', $uuid);

        $this->assertTrue($result);
    }

    public function test_delete()
    {
        $uuid = (string) Uuid::uuid4();
        $this->storage->create($this->schema);
        $this->storage->save('test', 'foo', $uuid, ['text' => 'value']);

        $this->storage->delete('test', 'foo', $uuid);

        $this->assertFalse($this->storage->has('test', 'foo', $uuid));
    }
}
