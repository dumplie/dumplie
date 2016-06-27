<?php

namespace Dumplie\Test\Integration\Application\InMemory\CustomerService;

use Dumplie\Application\Command\CustomerService\AcceptOrder;
use Dumplie\Application\Command\CustomerService\AcceptOrderHandler;
use Dumplie\Application\Command\CustomerService\PrepareOrder;
use Dumplie\Application\Command\CustomerService\PrepareOrderHandler;
use Dumplie\Application\Command\CustomerService\RefundOrder;
use Dumplie\Application\Command\CustomerService\RefundOrderHandler;
use Dumplie\Application\Command\CustomerService\RejectOrder;
use Dumplie\Application\Command\CustomerService\RejectOrderHandler;
use Dumplie\Application\Command\CustomerService\SendOrder;
use Dumplie\Application\Command\CustomerService\SendOrderHandler;
use Dumplie\Domain\CustomerService\Order;
use Dumplie\Domain\CustomerService\Orders;
use Dumplie\Infrastructure\InMemory\CustomerService\InMemoryOrders;

class OrderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Order
     */
    private $order;

    /**
     * @var Orders
     */
    private $orders;

    function setUp()
    {
        $this->order = new Order();
        $this->orders = new InMemoryOrders([$this->order]);
    }

    function test_accept_order()
    {
        $acceptCommand = new AcceptOrder($this->order->id());
        $acceptHandler = new AcceptOrderHandler($this->orders);
        $acceptHandler->handle($acceptCommand);
    }

    function test_reject_order()
    {
        $rejectCommand = new RejectOrder($this->order->id());
        $rejectHandler = new RejectOrderHandler($this->orders);
        $rejectHandler->handle($rejectCommand);
    }

    function test_prepare_order()
    {
        $acceptCommand = new AcceptOrder($this->order->id());
        $acceptHandler = new AcceptOrderHandler($this->orders);
        $acceptHandler->handle($acceptCommand);

        $prepareCommand = new PrepareOrder($this->order->id());
        $prepareHandler = new PrepareOrderHandler($this->orders);
        $prepareHandler->handle($prepareCommand);
    }

    function test_refund_order()
    {
        $acceptCommand = new AcceptOrder($this->order->id());
        $acceptHandler = new AcceptOrderHandler($this->orders);
        $acceptHandler->handle($acceptCommand);

        $refundCommand = new RefundOrder($this->order->id());
        $refundHandler = new RefundOrderHandler($this->orders);
        $refundHandler->handle($refundCommand);
    }

    function test_send_order()
    {
        $acceptCommand = new AcceptOrder($this->order->id());
        $acceptHandler = new AcceptOrderHandler($this->orders);
        $acceptHandler->handle($acceptCommand);

        $prepareCommand = new PrepareOrder($this->order->id());
        $prepareHandler = new PrepareOrderHandler($this->orders);
        $prepareHandler->handle($prepareCommand);

        $sendCommand = new SendOrder($this->order->id());
        $sendHandler = new SendOrderHandler($this->orders);
        $sendHandler->handle($sendCommand);
    }
}
