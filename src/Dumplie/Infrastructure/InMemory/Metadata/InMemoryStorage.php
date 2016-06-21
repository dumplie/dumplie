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

    public function update(Schema $schema)
    {
        // in memory schema is always up to date
    }

    public function drop(Schema $schema)
    {
        $this->storage = [];
    }

    /**
     * @param string $typeName
     * @param array $criteria
     * @return array
     */
    public function findBy(string $typeName, array $criteria = []) : array
    {
        if (!$this->typeExists($typeName)) {
            return [];
        }

        foreach ($this->storage[$typeName] as $id => $data) {
            if ($this->matchesCriteria($criteria, $data)) {
                return $data;
            }
        }

        return [];
    }

    /**
     * @param string $typeName
     * @param string $id
     * @param array $metadata
     */
    public function save(string $typeName, string $id, array $metadata = [])
    {
        if (!$this->typeExists($typeName)) {
            $this->storage[$typeName] = [];
        }

        $this->storage[$typeName][$id] = array_merge(['id' => $id], $metadata);
    }

    /**
     * @param string $typeName
     * @param string $id
     * @return bool
     */
    public function has(string $typeName, string $id) : bool
    {
        if (!$this->typeExists($typeName)) {
            return false;
        }

        return array_key_exists($id, $this->storage[$typeName]);
    }

    /**
     * @param string $typeName
     * @param string $id
     */
    public function delete(string $typeName, string $id)
    {
        if (!$this->typeExists($typeName)) {
            return ;
        }

        if (array_key_exists($id, $this->storage[$typeName])) {
            unset($this->storage[$typeName][$id]);
        }
    }

    /**
     * @param string $typeName
     * @return bool
     */
    private function typeExists(string $typeName)
    {
        return array_key_exists($typeName, $this->storage);
    }

    /**
     * @param array $criteria
     * @param $metadata
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