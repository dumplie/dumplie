<?php

namespace Dumplie\Test\Integration\Application\InMemory\Command\Customer;

use Dumplie\Application\Command\Customer\AddToCart;
use Dumplie\Application\Command\Customer\AddToCartHandler;
use Dumplie\Domain\Customer\Cart;
use Dumplie\Domain\Customer\Product;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;
use Dumplie\Infrastructure\InMemory\Customer\InMemoryCarts;
use Dumplie\Infrastructure\InMemory\Customer\InMemoryProducts;

class AddToCartHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    function it_allows_to_add_new_products_to_cart()
    {
        $cart = new Cart();
        $carts = new InMemoryCarts([$cart]);
        $skuCode = new SKU("SKU_1");
        $products = new InMemoryProducts([new Product($skuCode, Price::EUR(2500), true)]);

        $command = new AddToCart($skuCode, 2, (string) $cart->id());
        $handler = new AddToCartHandler($products, $carts);

        $handler->handle($command);

        $this->assertEquals(50.0, $cart->totalPrice()->floatValue());
    }
}
