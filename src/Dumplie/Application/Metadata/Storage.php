<?php

declare (strict_types = 1);

namespace Dumplie\Application\Metadata;

interface Storage
{
    /**
     * @param Schema $schema
     */
    public function update(Schema $schema);

    /**
     * @param Schema $schema
     */
    public function drop(Schema $schema);

    /**
     * Needs to return metadata in following format:
     *
     * [
     *   'id' => 'e94e4c36-3ffb-49b6-b8a5-973fa5c4aee6',
     *   'sku' => 'DUMPLIE_SKU_1',
     *   'name' => 'Product name'
     * ]
     *
     * Key 'id' is required.
     *
     * @param string $typeName
     * @param array $criteria
     * @return array
     */
    public function findBy(string $typeName, array $criteria = []) : array;

    /**
     * @param string $typeName
     * @param string $id
     * @param array $metadata
     * @return
     */
    public function save(string $typeName, string $id, array $metadata = []);

    /**
     * @param string $typeName
     * @param string $id
     * @return bool
     */
    public function has(string $typeName, string $id) : bool;

    /**
     * @param string $typeName
     * @param string $id
     * @return mixed
     */
    public function delete(string $typeName, string $id);
}