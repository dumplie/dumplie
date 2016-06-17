<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Customer;

use Dumplie\Application\Transaction\Factory;
use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Carts;
use Dumplie\Domain\Customer\Checkouts;
use Dumplie\Domain\Customer\Exception\OrderAlreadyExistsException;
use Dumplie\Domain\Customer\OrderId;
use Dumplie\Domain\Customer\Orders;
use Dumplie\Domain\Customer\Products;

final class PlaceOrderHandler
{
    /**
     * @var Carts
     */
    private $carts;

    /**
     * @var Checkouts
     */
    private $checkouts;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var Products
     */
    private $products;

    /**
     * @var Orders
     */
    private $orders;

    /**
     * @param Carts $carts
     * @param Products $products
     * @param Checkouts $checkouts
     * @param Orders $orders
     * @param Factory $factory
     */
    public function __construct(Carts $carts, Products $products, Checkouts $checkouts, Orders $orders, Factory $factory)
    {
        $this->carts = $carts;
        $this->checkouts = $checkouts;
        $this->factory = $factory;
        $this->products = $products;
        $this->orders = $orders;
    }

    /**
     * @param PlaceOrder $command
     */
    public function handle(PlaceOrder $command)
    {
        $cartId = new CartId($command->cartId());
        $orderId = new OrderId($command->orderId());
        $transaction = $this->factory->open();

        if ($this->orders->exists($orderId)) {
            throw OrderAlreadyExistsException::withId($orderId);
        }

        try {
            $checkout = $this->checkouts->getForCart($cartId);
            $order = $checkout->placeOrder($orderId, $this->products, $this->carts);

            $this->orders->add($order);

            $this->checkouts->removeForCart($cartId);
            $this->carts->remove($cartId);

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}