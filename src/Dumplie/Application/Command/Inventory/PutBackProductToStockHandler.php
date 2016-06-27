<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Inventory;

use Dumplie\Domain\Inventory\Exception\ProductNotFound;
use Dumplie\Domain\Inventory\Products;
use Dumplie\Domain\SharedKernel\Product\SKU;

final class PutBackProductToStockHandler
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
     * @param PutBackProductToStock $command
     * @throws ProductNotFound
     */
    public function handle(PutBackProductToStock $command)
    {
        $product = $this->products->getBySku(new SKU($command->sku()));
        $product->putBackToStock();
    }
}
