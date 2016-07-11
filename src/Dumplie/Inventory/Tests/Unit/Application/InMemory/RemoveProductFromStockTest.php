<?php

namespace Dumplie\Inventory\Tests\Unit\Application\InMemory;

use Dumplie\Inventory\Application\Command\RemoveProductFromStock;
use Dumplie\Inventory\Application\Command\RemoveProductFromStockHandler;
use Dumplie\Inventory\Domain\Product;
use Dumplie\SharedKernel\Domain\Money\Price;
use Dumplie\SharedKernel\Domain\Product\SKU;
use Dumplie\Inventory\Infrastructure\InMemory\InMemoryProducts;
use Dumplie\SharedKernel\Infrastructure\InMemory\Transaction\Factory;

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
