<?php

namespace Dumplie\Test\Integration\Application\InMemory\Inventory;

use Dumplie\Application\Command\Inventory\CreateProduct;
use Dumplie\Application\Command\Inventory\CreateProductHandler;
use Dumplie\Application\Command\Inventory\PutBackProductToStock;
use Dumplie\Application\Command\Inventory\PutBackProductToStockHandler;
use Dumplie\Application\Command\Inventory\RemoveProductFromStock;
use Dumplie\Application\Command\Inventory\RemoveProductFromStockHandler;
use Dumplie\Domain\Inventory\Product;
use Dumplie\Domain\Inventory\Products;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;
use Dumplie\Infrastructure\InMemory\Inventory\InMemoryProducts;

class ProductTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Products
     */
    private $products;

    /**
     * @var PutBackProductToStockHandler
     */
    private $putBackProductToStockHandler;

    /**
     * @var CreateProductHandler
     */
    private $createProductHandler;

    /**
     * @var RemoveProductFromStockHandler
     */
    private $removeProductFromStockHandler;

    function setUp()
    {
        $this->products = new InMemoryProducts([]);
        $this->createProductHandler = new CreateProductHandler(
            $this->products
        );
        $this->removeProductFromStockHandler = new RemoveProductFromStockHandler(
            $this->products
        );
        $this->putBackProductToStockHandler = new PutBackProductToStockHandler(
            $this->products
        );
    }

    function test_creating_product()
    {
        $this->createProductHandler->handle(new CreateProduct(
            'dumplie-sku-1',
            2000,
            'EUR',
            false
        ));

        $product = $this->products->getBySku(new SKU('dumplie-sku-1'));
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
        $this->createProductHandler->handle(new CreateProduct(
            'dumplie-sku-1',
            2000,
            'EUR',
            true
        ));

        $this
            ->removeProductFromStockHandler
            ->handle(new RemoveProductFromStock(
                'dumplie-sku-1'
            ))
        ;

        $product = $this->products->getBySku(new SKU('dumplie-sku-1'));
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
        $this->createProductHandler->handle(new CreateProduct(
            'dumplie-sku-1',
            2000,
            'EUR',
            true
        ));

        $this
            ->removeProductFromStockHandler
            ->handle(new RemoveProductFromStock(
                'dumplie-sku-1'
            ))
        ;

        $this
            ->putBackProductToStockHandler
            ->handle(new PutBackProductToStock(
                'dumplie-sku-1'
            ))
        ;

        $product = $this->products->getBySku(new SKU('dumplie-sku-1'));
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
