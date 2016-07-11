<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Infrastructure\Doctrine\Dbal\Domain;

use Doctrine\DBAL\Connection;
use Dumplie\Domain\Customer\Exception\ProductNotFoundException;
use Dumplie\Customer\Domain\Product;
use Dumplie\Customer\Domain\Products;
use Dumplie\SharedKernel\Domain\Money\Price;
use Dumplie\SharedKernel\Domain\Product\SKU;

final class DbalProducts implements Products
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
     * @param SKU $sku
     * @return Product
     * @throws ProductNotFoundException
     */
    public function getBySku(SKU $sku) : Product
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->select(
                'sku',
                'is_in_stock',
                'price_amount',
                'price_currency',
                'price_precision'
            )->from('dumplie_inventory_product')
            ->where('sku = :sku')
            ->setParameter('sku', (string) $sku)
        ;

        $result = $this->connection->fetchAssoc($queryBuilder->getSQL(), $queryBuilder->getParameters());

        if (!$result) {
            throw ProductNotFoundException::bySku($sku);
        }

        return new Product(
            new SKU($result['sku']),
            new Price((int) $result['price_amount'], $result['price_currency'], (int) $result['price_precision']),
            (bool) $result['is_in_stock']
        );
    }
}