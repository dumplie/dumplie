<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Infrastructure\InMemory;

use Dumplie\Customer\Domain\Exception\ProductNotFoundException;
use Dumplie\Customer\Domain\Product;
use Dumplie\Customer\Domain\Products;
use Dumplie\SharedKernel\Domain\Product\SKU;

final class InMemoryProducts implements Products
{
    /**
     * @var array
     */
    private $products;

    /**
     * @param array|Product[] $products
     */
    public function __construct(array $products = [])
    {
        $this->products = [];

        foreach ($products as $product) {
            if (!$product instanceof Product) {
                throw new \InvalidArgumentException();
            }

            $this->products[(string) $product->sku()] = $product;
        }
    }

    /**
     * @param SKU $sku
     *
     * @return Product
     *
     * @throws ProductNotFoundException
     */
    public function getBySku(SKU $sku) : Product
    {
        if (!array_key_exists((string) $sku, $this->products)) {
            throw ProductNotFoundException::bySku($sku);
        }

        return $this->products[(string) $sku];
    }
}
