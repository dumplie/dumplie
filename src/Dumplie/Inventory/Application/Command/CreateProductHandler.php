<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Application\Command;

use Dumplie\Inventory\Domain\Product;
use Dumplie\Inventory\Domain\Products;
use Dumplie\SharedKernel\Domain\Money\Price;
use Dumplie\SharedKernel\Domain\Product\SKU;

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
            new Price($createProduct->amount(), $createProduct->currency(), $createProduct->precision()),
            $createProduct->isInStock()
        ));
    }
}
