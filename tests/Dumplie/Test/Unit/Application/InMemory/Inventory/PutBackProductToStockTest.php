<?php

namespace Dumplie\Test\Unit\Application\InMemory\Inventory;

use Dumplie\Application\Command\Inventory\PutBackProductToStock;
use Dumplie\Application\Command\Inventory\PutBackProductToStockHandler;
use Dumplie\Domain\Inventory\Product;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;
use Dumplie\Infrastructure\InMemory\Inventory\InMemoryProducts;
use Dumplie\Infrastructure\InMemory\Transaction\Factory;

class PutBackProductToStockTest extends \PHPUnit_Framework_TestCase
{
    private $putBackProductToStockHandler;
    private $products;

    public function setUp()
    {
        $this->products = new InMemoryProducts([
            new Product(
                new SKU('dumplie_sku_1'),
                Price::fromInt(2000, 'EUR'),
                false
            )
        ]);
        
        $this->putBackProductToStockHandler = new PutBackProductToStockHandler(
            $this->products,
            new Factory()
        );
    }

    public function test_that_put_back_product_with_given_sku_to_stock()
    {
        $this->putBackProductToStockHandler->handle(
            new PutBackProductToStock('dumplie_sku_1')
        );

        $product = $this->products->getBySku(new SKU('dumplie_sku_1'));

        $this->assertEquals(
            new Product(
                new SKU('dumplie_sku_1'),
                Price::fromInt(2000, 'EUR'),
                true
            ),
            $product
        );
    }
}
