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

    public function findAll(int $limit, int $offset = 0) : array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from('dumplie_inventory_product')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $results = $this->connection->fetchAll($qb->getSQL(), $qb->getParameters());

        return array_map(function ($data) {
            return new Product('sku', 124.50, 'PLN', true);
        }, $results);
    }

    public function count() : int
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('COUNT(sku)')
            ->from('dumplie_inventory_product');

        return (int) $this->connection->fetchColumn($qb->getSQL(), $qb->getParameters());
    }
}