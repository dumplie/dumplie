<?php

declare (strict_types = 1);

namespace Dumplie\Test\Integration\Application\Generic\Inventory;

use Dumplie\Application\Command\Inventory\CreateProduct;
use Dumplie\Application\Command\Inventory\PutBackProductToStock;
use Dumplie\Application\Command\Inventory\RemoveProductFromStock;
use Dumplie\Domain\Inventory\Product;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;
use Dumplie\Test\Context\InventoryContext;

abstract class ProductTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InventoryContext
     */
    protected $inventoryContext;

    abstract protected function clear();

    function test_creating_product()
    {
        $this->inventoryContext->commandBus()->handle(new CreateProduct(
            'dumplie-sku-1',
            2000,
            'EUR',
            false
        ));

        $this->clear();
        $product = $this->inventoryContext->products()->getBySku(new SKU('dumplie-sku-1'));
        $this->assertEquals(
            new Product(
                new SKU('dumplie-sku-1'),
                Price::fromInt(2000, 'EUR'),
                false
            ),
            $product
        );
    }

    function test_that_removes_product_from_stock()
    {
        $this->inventoryContext->commandBus()
            ->handle(new CreateProduct(
                'dumplie-sku-1',
                2000,
                'EUR',
                true
            ));

        $this->inventoryContext->commandBus()
            ->handle(new RemoveProductFromStock('dumplie-sku-1'));

        $this->clear();
        $product = $this->inventoryContext->products()->getBySku(new SKU('dumplie-sku-1'));
        $this->assertEquals(
            new Product(
                new SKU('dumplie-sku-1'),
                Price::fromInt(2000, 'EUR'),
                false
            ),
            $product
        );
    }

    function test_that_put_back_product_to_stock()
    {
        $this->inventoryContext->commandBus()->handle(new CreateProduct(
            'dumplie-sku-1',
            2000,
            'EUR',
            true
        ));

        $this->inventoryContext->commandBus()->handle(new RemoveProductFromStock('dumplie-sku-1'));
        $this->inventoryContext->commandBus()->handle(new PutBackProductToStock('dumplie-sku-1'));

        $this->clear();
        $product = $this->inventoryContext->products()->getBySku(new SKU('dumplie-sku-1'));
        $this->assertEquals(
            new Product(
                new SKU('dumplie-sku-1'),
                Price::fromInt(2000, 'EUR'),
                true
            ),
            $product
        );
    }
}