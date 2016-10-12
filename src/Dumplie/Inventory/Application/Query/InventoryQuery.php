<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Application\Query;

use Dumplie\Inventory\Application\Exception\QueryException;
use Dumplie\Inventory\Application\Query\Result\Product;

interface InventoryQuery
{
    /**
     * @param string $sku
     * @return bool
     */
    public function skuExists(string $sku) : bool;

    /**
     * @param string $sku
     * @return Product
     * @throws QueryException
     */
    public function getBySku(string $sku) : Product;

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