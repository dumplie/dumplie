<?php

namespace Spec\Dumplie\CustomerService\Domain\Order;

use Dumplie\Customer\Domain\Event\CustomerPlacedOrder;
use Dumplie\Customer\Domain\OrderId;
use Dumplie\CustomerService\Domain\Order;
use Dumplie\CustomerService\Domain\Orders;
use Dumplie\SharedKernel\Domain\Event\Event;
use Dumplie\SharedKernel\Domain\Event\Events;
use Dumplie\SharedKernel\Domain\Event\Listener;
use Dumplie\SharedKernel\Domain\Exception\UnknownEventException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;

class NewOrderListenerSpec extends ObjectBehavior
{
    function let(Orders $orders)
    {
        $this->beConstructedWith($orders);
    }

    function it_is_domain_event_listener()
    {
        $this->shouldImplement(Listener::class);
    }

    function it_creates_new_customer_service_order_when_customer_place_order(Orders $orders)
    {
        $orders->add(Argument::type(Order::class))->shouldBeCalled();

        $this->on(json_encode([
            'name' => Events::CUSTOMER_PLACED_ORDER,
            'order_id' => (string) OrderId::generate(),
            'date' => '2015-01-01 12:12:12'
        ]));
    }

    function it_throws_exception_for_unexpected_events()
    {
        $this->shouldThrow(UnknownEventException::class)->during('on', [json_encode([
            'name' => 'unknown_event'
        ])]);
    }
}
