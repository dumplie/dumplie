<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService\Order;

use Dumplie\Domain\CustomerService\Order;
use Dumplie\Domain\CustomerService\OrderId;
use Dumplie\Domain\CustomerService\Orders;
use Dumplie\Domain\SharedKernel\Event\Events;
use Dumplie\Domain\SharedKernel\Event\Listener;
use Dumplie\Domain\SharedKernel\Exception\UnknownEventException;

final class NewOrderListener implements Listener
{
    /**
     * @var Orders
     */
    private $orders;

    /**
     * @param Orders $orders
     */
    public function __construct(Orders $orders)
    {
        $this->orders = $orders;
    }

    /**
     * @param string $eventJson
     * @throws UnknownEventException
     */
    public function on(string $eventJson)
    {
        $data = json_decode($eventJson, true);

        if (!array_key_exists('name', $data) || $data['name'] !== Events::CUSTOMER_PLACED_ORDER) {
            throw UnknownEventException::unexpected(Events::CUSTOMER_PLACED_ORDER, $data['name']);
        }

        $this->orders->add(new Order(new OrderId($data['order_id']), new \DateTimeImmutable($data['date'])));
    }
}