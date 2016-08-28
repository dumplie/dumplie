<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Infrastructure\Doctrine\DBAL\Query;

use Doctrine\DBAL\Connection;
use Dumplie\Inventory\Application\Query\InventoryQuery;
use Dumplie\Inventory\Application\Query\Result\Product;

final class DbalInventoryQuery implements InventoryQuery
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $sku
     * @return bool
     */
    public function skuExists(string $sku) : bool
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('COUNT(*)')
            ->from('dumplie_inventory_product')
            ->where('sku = :sku')
            ->setParameter('sku', $sku);

        return (bool) $this->connection->fetchColumn($qb->getSQL(), $qb->getParameters());
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAll(int $limit, int $offset = 0) : array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from('dumplie_inventory_product')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $results = $this->connection->fetchAll($qb->getSQL(), $qb->getParameters());

        return array_map(function ($data) {
            return new Product($data['sku'], $data['price_amount'] / $data['price_precision'], $data['price_currency'], (bool) $data['is_in_stock']);
        }, $results);
    }

    /**
     * @return int
     */
    public function count() : int
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('COUNT(sku)')
            ->from('dumplie_inventory_product');

        return (int) $this->connection->fetchColumn($qb->getSQL(), $qb->getParameters());
    }
}