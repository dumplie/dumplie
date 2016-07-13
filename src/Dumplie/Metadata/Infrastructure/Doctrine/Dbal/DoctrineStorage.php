<?php

declare(strict_types = 1);

namespace Dumplie\Metadata\Infrastructure\Doctrine\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema as DBALSchema;
use Dumplie\Metadata\Schema;
use Dumplie\Metadata\Schema\TypeSchema;
use Dumplie\Metadata\Storage;

class DoctrineStorage implements Storage
{
    use TableName;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var TypeRegistry
     */
    private $typeRegistry;

    /**
     * DoctrineStorage constructor.
     *
     * @param Connection   $connection
     * @param TypeRegistry $typeRegistry
     */
    public function __construct(Connection $connection, TypeRegistry $typeRegistry)
    {
        $this->connection = $connection;
        $this->typeRegistry = $typeRegistry;
    }

    /**
     * @param Schema $schema
     *
     * @throws DoctrineStorageException
     */
    public function create(Schema $schema)
    {
        $currentDbSchema = $this->connection->getSchemaManager()->createSchema();

        $dbSchema = new DBALSchema(
            $currentDbSchema->getTables(),
            $currentDbSchema->getSequences(),
            $this->connection->getSchemaManager()->createSchemaConfig()
        );

        foreach ($schema->types() as $type) {
            $tableName = $this->tableName($schema->name(), $type->name());

            if ($dbSchema->hasTable($tableName)) {
                throw DoctrineStorageException::tableAlreadyExists($tableName);
            }

            $this->createTable($dbSchema, $schema->name(), $type);
        }

        $queries = $dbSchema->toSql($this->connection->getDatabasePlatform());

        $this->executeQueries($queries);
    }

    /**
     * @param Schema $schema
     */
    public function alter(Schema $schema)
    {
        $currentSchema = $this->connection->getSchemaManager()->createSchema();
        $targetSchema = clone $currentSchema;

        foreach ($schema->types() as $type) {
            $tableName = $this->tableName($schema->name(), $type->name());

            if ($targetSchema->hasTable($tableName)) {
                $targetSchema->dropTable($tableName);
            }

            $this->createTable($targetSchema, $schema->name(), $type);
        }

        $queries = $currentSchema->getMigrateToSql($targetSchema, $this->connection->getDatabasePlatform());
        $this->executeQueries($queries);
    }

    /**
     * @param Schema $schema
     */
    public function drop(Schema $schema)
    {
        $currentSchema = $this->connection->getSchemaManager()->createSchema();
        $targetSchema = clone $currentSchema;

        foreach ($schema->types() as $type) {
            $targetSchema->dropTable($this->tableName($schema->name(), $type->name()));
        }

        $queries = $currentSchema->getMigrateToSql($targetSchema, $this->connection->getDatabasePlatform());
        $this->executeQueries($queries);
    }

    /**
     * Needs to return metadata in following format:
     * [
     *   'id' => 'e94e4c36-3ffb-49b6-b8a5-973fa5c4aee6',
     *   'sku' => 'DUMPLIE_SKU_1',
     *   'name' => 'Product name'
     * ]
     * Key 'id' is required.
     *
     * @param string $schema
     * @param string $typeName
     * @param array  $criteria
     *
     * @return array
     */
    public function findBy(string $schema, string $typeName, array $criteria = []) : array
    {
        $builder = $this->connection->createQueryBuilder();
        $builder->select('*');
        $builder->from($this->tableName($schema, $typeName));
        $builder->setMaxResults(1);

        foreach ($criteria as $field => $value) {
            $builder->andWhere(sprintf('%1$s = :%1$s', $field));
            $builder->setParameter($field, $value);
        }

        return $builder->execute()->fetch(\PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * @param string $schema
     * @param string $typeName
     * @param string $id
     * @param array  $metadata
     */
    public function save(string $schema, string $typeName, string $id, array $metadata = [])
    {
        if ($this->has($schema, $typeName, $id)) {
            $this->update($schema, $typeName, $id, $metadata);

            return;
        }

        $this->insert($schema, $typeName, $id, $metadata);
    }

    /**
     * @param string $schema
     * @param string $typeName
     * @param string $id
     *
     * @return bool
     */
    public function has(string $schema, string $typeName, string $id) : bool
    {
        return !!$this->connection->createQueryBuilder()
            ->select('id')
            ->from($this->tableName($schema, $typeName))
            ->where('id = :id')
            ->setParameter('id', $id)
            ->execute()
            ->fetchColumn();
    }

    /**
     * @param string $schema
     * @param string $typeName
     * @param string $id
     */
    public function delete(string $schema, string $typeName, string $id)
    {
        $query = $this->connection->createQueryBuilder()
            ->delete($this->tableName($schema, $typeName))
            ->where('id = :id')
            ->setParameter('id', $id);

        $this->connection->executeQuery($query->getSQL(), $query->getParameters());
    }

    /**
     * @param DBALSchema $schema
     * @param string     $schemaName
     * @param TypeSchema $type
     *
     * @throws DoctrineStorageException
     */
    private function createTable(DBALSchema $schema, string $schemaName, TypeSchema $type)
    {
        $table = $schema->createTable($this->tableName($schemaName, $type->name()));
        $table->addColumn('id', 'guid');
        $table->setPrimaryKey(['id']);

        foreach ($type->getDefinitions(['id']) as $field => $definition) {
            $this->typeRegistry->map($schemaName, $table, $field, $definition);
        }
    }

    /**
     * @param string $schema
     * @param string $typeName
     * @param string $id
     * @param array  $metadata
     */
    private function insert(string $schema, string $typeName, string $id, array $metadata)
    {
        $builder = $this->connection->createQueryBuilder();

        $builder->insert($this->tableName($schema, $typeName));
        $builder->setValue('id', $builder->createNamedParameter($id));

        foreach ($metadata as $field => $value) {
            $builder->setValue($field, $builder->createNamedParameter($value));
        }

        $builder->execute();
    }

    /**
     * @param string $schema
     * @param string $typeName
     * @param string $id
     * @param array  $metadata
     */
    private function update(string $schema, string $typeName, string $id, array $metadata)
    {
        $builder = $this->connection->createQueryBuilder();

        $builder->update($this->tableName($schema, $typeName));
        $builder->where('id = :id');
        $builder->setParameter('id', $id);

        foreach ($metadata as $field => $value) {
            $builder->set($field, $builder->createNamedParameter($value));
        }

        $builder->execute();
    }

    /**
     * @param array $queries
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function executeQueries(array $queries)
    {
        foreach ($queries as $query) {
            $this->connection->prepare($query)->execute();
        }
    }
}
