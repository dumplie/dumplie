<?php

namespace Dumplie\Test\Unit\Application\InMemory\Inventory;

use Dumplie\Application\Command\Inventory\RemoveProductFromStock;
use Dumplie\Application\Command\Inventory\RemoveProductFromStockHandler;
use Dumplie\Domain\Inventory\Product;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;
use Dumplie\Infrastructure\InMemory\Inventory\InMemoryProducts;
use Dumplie\Infrastructure\InMemory\Transaction\Factory;

class RemoveProductFromStockTest extends \PHPUnit_Framework_TestCase
{
    private $removeProductFromStockHandler;
    private $products;

    public function setUp()
    {
        $this->products = new InMemoryProducts([
            new Product(
                new SKU('dumplie_sku_1'),
                Price::fromInt(2000, 'EUR'),
                true
            )
        ]);
        
        $this->removeProductFromStockHandler = new RemoveProductFromStockHandler(
            $this->products,
            new Factory()
        );
    }

    public function test_that_remove_product_with_given_sku_from_stock()
    {
        $this->removeProductFromStockHandler->handle(
            new RemoveProductFromStock('dumplie_sku_1')
        );

        $product = $this->products->getBySku(new SKU('dumplie_sku_1'));

        $this->assertEquals(
            new Product(
                new SKU('dumplie_sku_1'),
                Price::fromInt(2000, 'EUR'),
                false
            ),
            $product
        );
    }
}
