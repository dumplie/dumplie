<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Tests\Integration\Application\Generic;

use Dumplie\CustomerService\Application\Command\CreatePayment;
use Dumplie\CustomerService\Application\Command\PayPayment;
use Dumplie\CustomerService\Application\Command\RejectPayment;
use Dumplie\CustomerService\Domain\OrderId;
use Dumplie\CustomerService\Domain\PaymentId;
use Dumplie\CustomerService\Tests\CustomerServiceContext;

abstract class PaymentTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CustomerServiceContext
     */
    protected $customerServiceContext;

    abstract protected function clear();

    function test_create_payment()
    {
        $orderId = OrderId::generate();
        $paymentId = PaymentId::generate();
        $this->customerServiceContext->customerPlacedOrder((string) $orderId);
        $command = new CreatePayment((string) $orderId, (string) $paymentId);

        $this->customerServiceContext->commandBus()->handle($command);

        $this->clear();
        $payment = $this->customerServiceContext->payments()->getById($paymentId);
        $this->assertFalse($payment->isPaid());
        $this->assertFalse($payment->isRejected());
    }

    function test_pay_payment()
    {
        $orderId = OrderId::generate();
        $this->customerServiceContext->customerPlacedOrder((string) $orderId);
        $paymentId = $this->customerServiceContext->createPaymentFor($orderId);
        $command = new PayPayment((string) $paymentId);

        $this->customerServiceContext->commandBus()->handle($command);

        $this->clear();
        $payment = $this->customerServiceContext->payments()->getById($paymentId);
        $this->assertTrue($payment->isPaid());
    }

    function test_reject_payment()
    {
        $orderId = OrderId::generate();
        $this->customerServiceContext->customerPlacedOrder((string) $orderId);
        $paymentId = $this->customerServiceContext->createPaymentFor($orderId);
        $command = new RejectPayment((string) $paymentId);

        $this->customerServiceContext->commandBus()->handle($command);

        $this->clear();
        $payment = $this->customerServiceContext->payments()->getById($paymentId);
        $this->assertTrue($payment->isRejected());
    }

}