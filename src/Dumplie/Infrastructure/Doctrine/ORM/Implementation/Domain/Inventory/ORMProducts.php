<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\Doctrine\ORM\Implementation\Domain\Inventory;

use Doctrine\ORM\EntityManager;
use Dumplie\Domain\Inventory\Exception\ProductNotFound;
use Dumplie\Domain\Inventory\Product;
use Dumplie\Domain\Inventory\Products;
use Dumplie\Domain\SharedKernel\Product\SKU;

final class ORMProducts implements Products
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param SKU $SKU
     * @return Product
     */
    public function getBySku(SKU $SKU): Product
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['sku.code' => $SKU]);

        if ($product === null) {
            ProductNotFound::bySku($SKU);
        }

        return $product;
    }

    /**
     * @param Product $product
     */
    public function add(Product $product)
    {
        $this->entityManager->persist($product);
    }
}