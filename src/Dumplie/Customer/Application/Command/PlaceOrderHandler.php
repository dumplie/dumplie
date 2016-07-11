<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Command;

use Dumplie\SharedKernel\Application\EventLog;
use Dumplie\Customer\Application\Command\PlaceOrder;
use Dumplie\Customer\Domain\CartId;
use Dumplie\Customer\Domain\Carts;
use Dumplie\Customer\Domain\Checkouts;
use Dumplie\Customer\Domain\Exception\OrderAlreadyExistsException;
use Dumplie\Customer\Domain\OrderId;
use Dumplie\Customer\Domain\Orders;
use Dumplie\Customer\Domain\Products;
use Dumplie\Customer\Domain\Event\CustomerPlacedOrder;

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
     * @var Products
     */
    private $products;

    /**
     * @var Orders
     */
    private $orders;

    /**
     * @var EventLog
     */
    private $eventLog;

    /**
     * @param Carts $carts
     * @param Products $products
     * @param Checkouts $checkouts
     * @param Orders $orders
     */
    public function __construct(Carts $carts, Products $products, Checkouts $checkouts, Orders $orders, EventLog $eventLog)
    {
        $this->carts = $carts;
        $this->checkouts = $checkouts;
        $this->products = $products;
        $this->orders = $orders;
        $this->eventLog = $eventLog;
    }

    /**
     * @param PlaceOrder $command
     * @throws OrderAlreadyExistsException
     * @throws \Dumplie\Customer\Domain\Exception\EmptyCartException
     */
    public function handle(PlaceOrder $command)
    {
        $cartId = new CartId($command->cartId());
        $orderId = new OrderId($command->orderId());


        if ($this->orders->exists($orderId)) {
            throw OrderAlreadyExistsException::withId($orderId);
        }

        $checkout = $this->checkouts->getForCart($cartId);
        $order = $checkout->placeOrder($orderId, $this->products, $this->carts);

        $this->orders->add($order);

        $this->checkouts->removeForCart($cartId);
        $this->carts->remove($cartId);

        $this->eventLog->log(new CustomerPlacedOrder((string) $orderId));
    }
}