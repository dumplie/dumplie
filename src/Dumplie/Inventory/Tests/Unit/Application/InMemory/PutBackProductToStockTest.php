<?php

namespace Dumplie\Inventory\Tests\Unit\Application\InMemory;

use Dumplie\Inventory\Application\Command\PutBackProductToStock;
use Dumplie\Inventory\Application\Command\PutBackProductToStockHandler;
use Dumplie\Inventory\Domain\Product;
use Dumplie\SharedKernel\Domain\Money\Price;
use Dumplie\SharedKernel\Domain\Product\SKU;
use Dumplie\Inventory\Infrastructure\InMemory\InMemoryProducts;
use Dumplie\SharedKernel\Infrastructure\InMemory\Transaction\Factory;

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
