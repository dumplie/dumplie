<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Tests\Integration\Application\Generic;

use Dumplie\CustomerService\Application\Command\AcceptOrder;
use Dumplie\CustomerService\Application\Command\PrepareOrder;
use Dumplie\CustomerService\Application\Command\RefundOrder;
use Dumplie\CustomerService\Application\Command\RejectOrder;
use Dumplie\CustomerService\Application\Command\SendOrder;
use Dumplie\CustomerService\Tests\CustomerServiceContext;
use Ramsey\Uuid\Uuid;

abstract class OrderTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CustomerServiceContext
     */
    protected $customerServiceContext;

    function test_accept_order()
    {
        $orderId = (string) Uuid::uuid4();
        $this->customerServiceContext->customerPlacedOrder($orderId);

        $acceptCommand = new AcceptOrder($orderId);

        $this->customerServiceContext->commandBus()->handle($acceptCommand);
    }

    function test_reject_order()
    {
        $orderId = (string) Uuid::uuid4();
        $this->customerServiceContext->customerPlacedOrder($orderId);

        $rejectCommand = new RejectOrder($orderId);

        $this->customerServiceContext->commandBus()->handle($rejectCommand);
    }

    function test_prepare_order()
    {
        $orderId = (string) Uuid::uuid4();
        $this->customerServiceContext->customerPlacedOrder($orderId);
        $acceptCommand = new AcceptOrder($orderId);

        $this->customerServiceContext->commandBus()->handle($acceptCommand);

        $prepareCommand = new PrepareOrder($orderId);
        $this->customerServiceContext->commandBus()->handle($prepareCommand);
    }

    function test_refund_order()
    {
        $orderId = (string) Uuid::uuid4();
        $this->customerServiceContext->customerPlacedOrder($orderId);
        $acceptCommand = new AcceptOrder($orderId);

        $this->customerServiceContext->commandBus()->handle($acceptCommand);

        $refundCommand = new RefundOrder($orderId);
        $this->customerServiceContext->commandBus()->handle($refundCommand);
    }

    function test_send_order()
    {
        $orderId = (string) Uuid::uuid4();
        $this->customerServiceContext->customerPlacedOrder($orderId);
        $acceptCommand = new AcceptOrder($orderId);

        $this->customerServiceContext->commandBus()->handle($acceptCommand);

        $prepareCommand = new PrepareOrder($orderId);
        $this->customerServiceContext->commandBus()->handle($prepareCommand);

        $sendCommand = new SendOrder($orderId);
        $this->customerServiceContext->commandBus()->handle($sendCommand);
    }
}