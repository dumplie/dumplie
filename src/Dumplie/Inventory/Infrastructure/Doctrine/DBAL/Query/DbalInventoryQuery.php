<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Infrastructure\Doctrine\DBAL\Query;

use Doctrine\DBAL\Connection;
use Dumplie\Inventory\Application\Exception\QueryException;
use Dumplie\Inventory\Application\Extension\Metadata;
use Dumplie\Inventory\Application\Query\InventoryQuery;
use Dumplie\Inventory\Application\Query\Result\Product;
use Dumplie\Metadata\MetadataAccessObject;
use Dumplie\Metadata\MetadataAccessRegistry;

final class DbalInventoryQuery implements InventoryQuery
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var MetadataAccessObject
     */
    private $mao;

    /**
     * @param Connection $connection
     * @param MetadataAccessRegistry $accessRegistry
     */
    public function __construct(Connection $connection, MetadataAccessRegistry $accessRegistry)
    {
        $this->connection = $connection;
        $this->mao = $accessRegistry->getMAO(Metadata::TYPE_NAME);
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
     * @param string $sku
     * @return Product
     * @throws QueryException
     */
    public function getBySku(string $sku) : Product
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from('dumplie_inventory_product')
            ->where('sku = :sku')
            ->setParameter('sku', $sku);

        $productData = $this->connection->fetchAssoc($qb->getSQL(), $qb->getParameters());

        if (empty($productData)) {
            throw QueryException::productNotFound($sku);
        }

        return new Product(
            $productData['sku'],
            $productData['price_amount'] / $productData['price_precision'],
            $productData['price_currency'],
            (bool) $productData['is_in_stock'],
            $this->mao->getBy([Metadata::FIELD_SKU => $productData['sku']])
        );
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
            return new Product(
                $data['sku'],
                $data['price_amount'] / $data['price_precision'],
                $data['price_currency'],
                (bool) $data['is_in_stock'],
                $this->mao->getBy([Metadata::FIELD_SKU => $data['sku']])
            );
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