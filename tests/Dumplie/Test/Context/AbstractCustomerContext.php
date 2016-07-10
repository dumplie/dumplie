<?php

declare (strict_types = 1);

namespace Dumplie\Test\Context;

use Dumplie\Application\Command\Customer\AddToCart;
use Dumplie\Application\Command\Customer\AddToCartHandler;
use Dumplie\Application\Command\Customer\ChangeBillingAddress;
use Dumplie\Application\Command\Customer\ChangeBillingAddressHandler;
use Dumplie\Application\Command\Customer\ChangeShippingAddress;
use Dumplie\Application\Command\Customer\ChangeShippingAddressHandler;
use Dumplie\Application\Command\Customer\CreateCart;
use Dumplie\Application\Command\Customer\CreateCartHandler;
use Dumplie\Application\Command\Customer\NewCheckout;
use Dumplie\Application\Command\Customer\NewCheckoutHandler;
use Dumplie\Application\Command\Customer\PlaceOrder;
use Dumplie\Application\Command\Customer\PlaceOrderHandler;
use Dumplie\Application\Command\Customer\RemoveFromCart;
use Dumplie\Application\Command\Customer\RemoveFromCartHandler;
use Dumplie\Application\CommandBus;
use Dumplie\Application\EventLog;
use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Carts;
use Dumplie\Domain\Customer\Checkouts;
use Dumplie\Domain\Customer\Orders;
use Dumplie\Domain\Customer\Products;

abstract class AbstractCustomerContext implements CustomerContext
{
    /**
     * @var Carts
     */
    protected $carts;

    /**
     * @var Orders
     */
    protected $orders;

    /**
     * @var Checkouts
     */
    protected $checkouts;

    /**
     * @var Products
     */
    protected $products;

    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * @return CommandBus
     */
    public function commandBus() : CommandBus
    {
        return $this->commandBus;
    }

    /**
     * @return Carts
     */
    public function carts() : Carts
    {
        return $this->carts;
    }

    /**
     * @return Orders
     */
    public function orders() : Orders
    {
        return $this->orders;
    }

    /**
     * @return Checkouts
     */
    public function checkouts() : Checkouts
    {
        return $this->checkouts;
    }

    /**
     * @return Products
     */
    public function products() : Products
    {
        return $this->products;
    }

    /**
     * @param $cartId
     * @throws \Dumplie\Domain\Customer\Exception\CartNotFoundException
     * @throws \Dumplie\Domain\Customer\Exception\CheckoutAlreadyExistsException
     */
    public function checkout(CartId $cartId)
    {
        $command = new NewCheckout(
            (string) $cartId,
            'Norbert Orzechowicz',
            'ul. Floriańska 5',
            '30-300',
            'Kraków',
            'PL'
        );

        $this->commandBus->handle($command);
    }

    /**
     * @param string $currency
     * @return CartId
     */
    public function createEmptyCart(string $currency = 'EUR') : CartId
    {
        $cartId = CartId::generate();
        $command = new CreateCart((string) $cartId, $currency);

        $this->commandBus->handle($command);

        return $cartId;
    }

    /**
     * @param string $currency
     * @param array $skuCodes
     *
     * @return CartId
     */
    public function createNewCartWithProducts(string $currency = 'EUR', array $skuCodes = []) : CartId
    {
        $cartId = CartId::generate();
        $command = new CreateCart((string) $cartId, $currency);

        $this->commandBus->handle($command);

        foreach ($skuCodes as $sku) {
            $addToCartCommand = new AddToCart($sku, 1, (string) $cartId);

            $this->commandBus->handle($addToCartCommand);

        }

        return $cartId;
    }

    /**
     * @param EventLog $eventLog
     * @param CommandBusFactory $commandBusFactory
     * @param array $commandExtension
     * @return CommandBus
     */
    protected function createCommandBus(EventLog $eventLog, CommandBusFactory $commandBusFactory, array $commandExtension = []) : CommandBus
    {
        return $commandBusFactory->create(
            [
                AddToCart::class => new AddToCartHandler($this->products, $this->carts),
                ChangeBillingAddress::class => new ChangeBillingAddressHandler($this->checkouts),
                ChangeShippingAddress::class => new ChangeShippingAddressHandler($this->checkouts),
                CreateCart::class => new CreateCartHandler($this->carts),
                NewCheckout::class => new NewCheckoutHandler($this->checkouts, $this->carts),
                PlaceOrder::class => new PlaceOrderHandler($this->carts, $this->products, $this->checkouts, $this->orders, $eventLog),
                RemoveFromCart::class => new RemoveFromCartHandler($this->carts)
            ],
            $commandExtension
        );
    }
}