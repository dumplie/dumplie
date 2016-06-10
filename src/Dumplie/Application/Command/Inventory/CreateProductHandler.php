<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Inventory;

use Dumplie\Application\Transaction\Factory;
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
     * @var Factory
     */
    private $transactionFactory;

    /**
     * CreateProductHandler constructor.
     *
     * @param Products $products
     * @param Factory  $transactionFactory
     */
    public function __construct(Products $products, Factory $transactionFactory)
    {
        $this->products = $products;
        $this->transactionFactory = $transactionFactory;
    }

    /**
     * @param CreateProduct $createProduct
     *
     * @throws \Exception
     */
    public function handle(CreateProduct $createProduct)
    {
        $transaction = $this->transactionFactory->open();

        try {
            $this->products->add(new Product(
                new SKU($createProduct->sku()),
                Price::fromInt($createProduct->amount(), $createProduct->currency()),
                $createProduct->isInStock()
            ));
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}
