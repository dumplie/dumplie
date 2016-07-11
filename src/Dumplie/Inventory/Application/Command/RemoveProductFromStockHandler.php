<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Application\Command;

use Dumplie\SharedKernel\Application\Transaction\Factory;
use Dumplie\Inventory\Domain\Exception\ProductNotFound;
use Dumplie\Inventory\Domain\Products;
use Dumplie\SharedKernel\Domain\Product\SKU;

final class RemoveProductFromStockHandler
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
     * @param RemoveProductFromStock $command
     * @throws ProductNotFound
     */
    public function handle(RemoveProductFromStock $command)
    {
        $product = $this->products->getBySku(new SKU($command->sku()));
        $product->removeFromStock();
    }
}
