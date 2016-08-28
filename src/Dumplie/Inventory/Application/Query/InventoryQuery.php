<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Application\Query;

interface InventoryQuery
{
    /**
     * @param string $sku
     * @return bool
     */
    public function skuExists(string $sku) : bool;

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAll(int $limit, int $offset = 0) : array;

    /**
     * @return int
     */
    public function count() : int;
}