<?php

declare (strict_types = 1);

namespace Dumplie\Test\Integration\Application\InMemory\Customer;

use Dumplie\Application\Command\Customer\AddToCart;
use Dumplie\Application\Command\Customer\AddToCartHandler;
use Dumplie\Application\Command\Customer\CreateCart;
use Dumplie\Application\Command\Customer\CreateCartHandler;
use Dumplie\Application\Command\Customer\NewCheckout;
use Dumplie\Application\Command\Customer\NewCheckoutHandler;
use Dumplie\Application\Command\Customer\PlaceOrder;
use Dumplie\Application\Command\Customer\PlaceOrderHandler;
use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Carts;
use Dumplie\Domain\Customer\Checkouts;
use Dumplie\Domain\Customer\OrderId;
use Dumplie\Domain\Customer\Orders;
use Dumplie\Domain\Customer\Product;
use Dumplie\Domain\Customer\Products;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;
use Dumplie\Infrastructure\InMemory\Customer\InMemoryCarts;
use Dumplie\Infrastructure\InMemory\Customer\InMemoryCheckouts;
use Dumplie\Infrastructure\InMemory\Customer\InMemoryOrders;
use Dumplie\Infrastructure\InMemory\Customer\InMemoryProducts;

final class OrderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Checkouts
     */
    private $checkouts;

    /**
     * @var Carts
     */
    private $carts;

    /**
     * @var Products
     */
    private $products;

    /**
     * @var Orders
     */
    private $orders;

    function setUp()
    {
        $this->checkouts = new InMemoryCheckouts();
        $this->carts = new InMemoryCarts();
        $this->products = new InMemoryProducts(array(
            new Product(new SKU('SKU_1'), Price::EUR(2500), true),
            new Product(new SKU('SKU_2'), Price::EUR(2500), true)
        ));
        $this->orders = new InMemoryOrders();
    }

    function test_placing_new_order()
    {
        $cartId = $this->createNewCartWithProducts('EUR', ['SKU_1', 'SKU_2']);

        $this->checkout($cartId);

        $orderId = OrderId::generate();
        $placeOrderCommand = new PlaceOrder((string) $cartId, (string) $orderId);
        $placeOrderHandler = new PlaceOrderHandler($this->carts, $this->products, $this->checkouts, $this->orders);

        $placeOrderHandler->handle($placeOrderCommand);

        $this->assertFalse($this->carts->exists($cartId));
        $this->assertFalse($this->checkouts->existsForCart($cartId));
        $this->assertTrue($this->orders->exists($orderId));
    }

    /**
     * @expectedException  \Dumplie\Domain\Customer\Exception\OrderAlreadyExistsException
     */
    function test_placing_order_with_the_same_id_twice()
    {
        $cartId = $this->createNewCartWithProducts('EUR', ['SKU_1', 'SKU_2']);

        $this->checkout($cartId);

        $orderId = OrderId::generate();
        $placeOrderCommand = new PlaceOrder((string) $cartId, (string) $orderId);
        $placeOrderHandler = new PlaceOrderHandler($this->carts, $this->products, $this->checkouts, $this->orders);

        $placeOrderHandler->handle($placeOrderCommand);

        $placeOrderCommand = new PlaceOrder((string) $cartId, (string) $orderId);
        $placeOrderHandler = new PlaceOrderHandler($this->carts, $this->products, $this->checkouts, $this->orders);

        $placeOrderHandler->handle($placeOrderCommand);
    }

    /**
     * @param string $currency
     * @return CartId
     */
    private function createNewCartWithProducts($currency = 'EUR', array $skuCodes = [])
    {
        $cartId = CartId::generate();
        $command = new CreateCart((string) $cartId, $currency);
        $handler = new CreateCartHandler($this->carts);

        $handler->handle($command);

        $addToCartHandler = new AddToCartHandler($this->products, $this->carts);

        foreach ($skuCodes as $sku) {
            $addToCartCommand = new AddToCart($sku, 1, (string) $cartId);
            $addToCartHandler->handle($addToCartCommand);
        }

        return $cartId;
    }

    /**
     * @param $cartId
     * @throws \Dumplie\Domain\Customer\Exception\CartNotFoundException
     * @throws \Dumplie\Domain\Customer\Exception\CheckoutAlreadyExistsException
     */
    private function checkout($cartId)
    {
        $checkoutCommand = new NewCheckout(
            (string)$cartId,
            'Norbert Orzechowicz',
            'ul. Floriańska 5',
            '30-300',
            'Kraków',
            'PL'
        );
        $checkoutHandler = new NewCheckoutHandler($this->checkouts, $this->carts);
        $checkoutHandler->handle($checkoutCommand);
    }
}