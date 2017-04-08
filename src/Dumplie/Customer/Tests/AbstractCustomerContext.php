<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Tests;

use Dumplie\Customer\Application\Command\AddToCart;
use Dumplie\Customer\Application\Command\AddToCartHandler;
use Dumplie\Customer\Application\Command\ChangeBillingAddress;
use Dumplie\Customer\Application\Command\ChangeBillingAddressHandler;
use Dumplie\Customer\Application\Command\ChangeShippingAddress;
use Dumplie\Customer\Application\Command\ChangeShippingAddressHandler;
use Dumplie\Customer\Application\Command\CreateCart;
use Dumplie\Customer\Application\Command\CreateCartHandler;
use Dumplie\Customer\Application\Command\NewCheckout;
use Dumplie\Customer\Application\Command\NewCheckoutHandler;
use Dumplie\Customer\Application\Command\PlaceOrder;
use Dumplie\Customer\Application\Command\PlaceOrderHandler;
use Dumplie\Customer\Application\Command\RemoveFromCart;
use Dumplie\Customer\Application\Command\RemoveFromCartHandler;
use Dumplie\SharedKernel\Application\CommandBus;
use Dumplie\SharedKernel\Application\EventLog;
use Dumplie\Customer\Domain\CartId;
use Dumplie\Customer\Domain\Carts;
use Dumplie\Customer\Domain\Checkouts;
use Dumplie\Customer\Domain\Orders;
use Dumplie\Customer\Domain\Products;
use Dumplie\SharedKernel\Tests\Context\CommandBusFactory;

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
     * @throws \Dumplie\Customer\Domain\Exception\CartNotFoundException
     * @throws \Dumplie\Customer\Domain\Exception\CheckoutAlreadyExistsException
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

    public function createNewCheckoutFromCart(CartId $cartId) : CartId
    {
        $command = new NewCheckout(
            (string) $cartId,
            'Joe Dean Anderson',
            'Street Avenue',
            '10-10',
            'Somewhereshire',
            'UK'
        );

        $this->commandBus->handle($command);

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