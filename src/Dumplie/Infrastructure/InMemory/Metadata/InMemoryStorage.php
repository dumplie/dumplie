<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\InMemory\Metadata;

use Dumplie\Application\Metadata\Schema;
use Dumplie\Application\Metadata\Storage;

final class InMemoryStorage implements Storage
{
    private $storage;

    public function __construct()
    {
        $this->storage = [];
    }

    public function create(Schema $schema)
    {
        // in memory schema is always up to date
    }

    public function alter(Schema $schema)
    {
        // in memory schema is always up to date
    }

    public function drop(Schema $schema)
    {
        $this->storage = [];
    }

    /**
     * @param string $schema
     * @param string $typeName
     * @param array  $criteria
     *
     * @return array
     */
    public function findBy(string $schema, string $typeName, array $criteria = []) : array
    {
        if (!$this->typeExists($schema, $typeName)) {
            return [];
        }

        foreach ($this->storage[$schema][$typeName] as $id => $data) {
            if ($this->matchesCriteria($criteria, $data)) {
                return $data;
            }
        }

        return [];
    }

    /**
     * @param string $schema
     * @param string $typeName
     * @param string $id
     * @param array  $metadata
     */
    public function save(string $schema, string $typeName, string $id, array $metadata = [])
    {
        if (!$this->schemaExists($schema)) {
            $this->storage[$schema] = [];
        }

        if (!$this->typeExists($schema, $typeName)) {
            $this->storage[$schema][$typeName] = [];
        }

        $this->storage[$schema][$typeName][$id] = array_merge(['id' => $id], $metadata);
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
        if (!$this->typeExists($schema, $typeName)) {
            return false;
        }

        return array_key_exists($id, $this->storage[$schema][$typeName]);
    }

    /**
     * @param string $schema
     * @param string $typeName
     * @param string $id
     */
    public function delete(string $schema, string $typeName, string $id)
    {
        if (!$this->typeExists($schema, $typeName)) {
            return;
        }

        if (array_key_exists($id, $this->storage[$schema][$typeName])) {
            unset($this->storage[$schema][$typeName][$id]);
        }
    }

    /**
     * @param string $schema
     *
     * @return bool
     */
    private function schemaExists(string $schema) : bool
    {
        return array_key_exists($schema, $this->storage);
    }

    /**
     * @param string $schema
     * @param string $typeName
     *
     * @return bool
     */
    private function typeExists(string $schema, string $typeName) : bool
    {
        if (!$this->schemaExists($schema)) {
            return false;
        }

        return array_key_exists($typeName, $this->storage[$schema]);
    }

    /**
     * @param array $criteria
     * @param       $metadata
     *
     * @return bool
     */
    private function matchesCriteria(array $criteria, array $metadata)
    {
        foreach ($criteria as $key => $value) {
            if (!array_key_exists($key, $metadata) && $metadata[$key] !== $value) {
                return false;
            }
        }

        return true;
    }
}
