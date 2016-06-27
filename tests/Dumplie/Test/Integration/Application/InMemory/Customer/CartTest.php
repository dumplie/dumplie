<?php

namespace Dumplie\Test\Integration\Application\InMemory\Customer;

use Dumplie\Application\Command\Customer\AddToCart;
use Dumplie\Application\Command\Customer\AddToCartHandler;
use Dumplie\Application\Command\Customer\CreateCart;
use Dumplie\Application\Command\Customer\CreateCartHandler;
use Dumplie\Application\Command\Customer\RemoveFromCart;
use Dumplie\Application\Command\Customer\RemoveFromCartHandler;
use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Carts;
use Dumplie\Domain\Customer\Product;
use Dumplie\Domain\Customer\Products;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;
use Dumplie\Infrastructure\InMemory\Customer\InMemoryCarts;
use Dumplie\Infrastructure\InMemory\Customer\InMemoryProducts;

class CartTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Carts
     */
    private $carts;

    /**
     * @var Products
     */
    private $products;

    function setUp()
    {
        $this->carts = new InMemoryCarts();
        $this->products = new InMemoryProducts([
            new Product(new SKU('SKU_1'), Price::EUR(2500), true),
            new Product(new SKU('SKU_2'), Price::EUR(2500), true)
        ]);
    }

    function test_adding_new_products_to_cart()
    {
        $cartId = $this->createNewCart('EUR');

        $command = new AddToCart('SKU_1', 2, (string) $cartId);
        $handler = new AddToCartHandler($this->products, $this->carts);

        $handler->handle($command);

        $cart = $this->carts->getById($cartId);

        $this->assertCount(1, $cart->items());
    }

    function test_removing_products_from_cart()
    {
        $cartId = $this->createNewCart('EUR');

        $addCommand = new AddToCart('SKU_1', 2, (string) $cartId);
        $addHandler = new AddToCartHandler($this->products, $this->carts);
        $addHandler->handle($addCommand);

        $removeCommand = new RemoveFromCart((string) $cartId, 'SKU_1');
        $removeHandler = new RemoveFromCartHandler($this->carts);
        $removeHandler->handle($removeCommand);

        $cart = $this->carts->getById($cartId);

        $this->assertTrue($cart->isEmpty());
    }

    /**
     * @param string $currency
     * @return CartId
     */
    private function createNewCart($currency = 'EUR')
    {
        $cartId = CartId::generate();
        $command = new CreateCart($cartId, $currency);
        $handler = new CreateCartHandler($this->carts);

        $handler->handle($command);

        return $cartId;
    }
}
