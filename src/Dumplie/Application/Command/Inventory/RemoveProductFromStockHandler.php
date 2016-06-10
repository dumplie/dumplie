<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Inventory;

use Dumplie\Application\Transaction\Factory;
use Dumplie\Domain\Inventory\Products;
use Dumplie\Domain\SharedKernel\Product\SKU;

final class RemoveProductFromStockHandler
{
    /**
     * @var Products
     */
    private $products;

    /**
     * @var Factory
     */
    private $transactionFactory;

    public function __construct(Products $products, Factory $factory)
    {
        $this->products = $products;
        $this->transactionFactory = $factory;
    }

    public function handle(RemoveProductFromStock $command)
    {
        $product = $this->products->getBySku(new SKU($command->sku()));

        $transaction = $this->transactionFactory->open();

        try {
            $product->removeFromStock();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}
