<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Inventory;

use Dumplie\Domain\Inventory\Product;
use Dumplie\Domain\Inventory\Products;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;

final class CreateProductHandler
{
    /**
     * @var Products
     */
    private $products;

    /**
     * @param Products $products
     */
    public function __construct(Products $products)
    {
        $this->products = $products;
    }

    /**
     * @param CreateProduct $createProduct
     *
     * @throws \Exception
     */
    public function handle(CreateProduct $createProduct)
    {
        $this->products->add(new Product(
            new SKU($createProduct->sku()),
            Price::fromInt($createProduct->amount(), $createProduct->currency()),
            $createProduct->isInStock()
        ));
    }
}
