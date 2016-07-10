<?php

declare (strict_types = 1);

namespace Dumplie\Test\Context;

use Dumplie\Application\Command\CustomerService\AcceptOrder;
use Dumplie\Application\Command\CustomerService\AcceptOrderHandler;
use Dumplie\Application\Command\CustomerService\CreatePayment;
use Dumplie\Application\Command\CustomerService\CreatePaymentHandler;
use Dumplie\Application\Command\CustomerService\PayPayment;
use Dumplie\Application\Command\CustomerService\PayPaymentHandler;
use Dumplie\Application\Command\CustomerService\PrepareOrder;
use Dumplie\Application\Command\CustomerService\PrepareOrderHandler;
use Dumplie\Application\Command\CustomerService\RefundOrder;
use Dumplie\Application\Command\CustomerService\RefundOrderHandler;
use Dumplie\Application\Command\CustomerService\RejectOrder;
use Dumplie\Application\Command\CustomerService\RejectOrderHandler;
use Dumplie\Application\Command\CustomerService\RejectPayment;
use Dumplie\Application\Command\CustomerService\RejectPaymentHandler;
use Dumplie\Application\Command\CustomerService\SendOrder;
use Dumplie\Application\Command\CustomerService\SendOrderHandler;
use Dumplie\Application\CommandBus;
use Dumplie\Application\EventLog;
use Dumplie\Application\Transaction\Factory;
use Dumplie\Domain\CustomerService\OrderId;
use Dumplie\Domain\CustomerService\Orders;
use Dumplie\Domain\CustomerService\PaymentId;
use Dumplie\Domain\CustomerService\Payments;
use Dumplie\Domain\Customer\Event\CustomerPlacedOrder;

abstract class AbstractCustomerServiceContext implements CustomerServiceContext
{
    /**
     * @var Orders
     */
    protected $orders;

    /**
     * @var Payments
     */
    protected $payments;

    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * @var EventLog
     */
    protected $eventLog;

    /**
     * @var Factory
     */
    protected $transactionFactory;

    public function commandBus() : CommandBus
    {
        return $this->commandBus;
    }

    /**
     * @return Orders
     */
    public function orders() : Orders
    {
        return $this->orders;
    }

    /**
     * @return Payments
     */
    public function payments() : Payments
    {
        return $this->payments;
    }

    /**
     * @param string $orderId
     */
    public function customerPlacedOrder(string $orderId)
    {
        $transaction = $this->transactionFactory->open();
        try {
            $this->eventLog->log(new CustomerPlacedOrder($orderId));
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }

    /**
     * @param OrderId $orderId
     * @return PaymentId
     */
    public function createPaymentFor(OrderId $orderId) : PaymentId
    {
        $paymentId = PaymentId::generate();
        $command = new CreatePayment((string) $orderId, (string) $paymentId);

        $this->commandBus->handle($command);

        return $paymentId;
    }

    /**
     * @param CommandBusFactory $commandBusFactory
     * @param array $commandExtensions
     * @return CommandBus
     */
    protected function createCommandBus(CommandBusFactory $commandBusFactory, array $commandExtensions = []) : CommandBus
    {
        return $commandBusFactory->create(
            [
                AcceptOrder::class => new AcceptOrderHandler($this->orders),
                CreatePayment::class => new CreatePaymentHandler($this->orders, $this->payments),
                PayPayment::class => new PayPaymentHandler($this->payments),
                RejectPayment::class => new RejectPaymentHandler($this->payments),
                PrepareOrder::class => new PrepareOrderHandler($this->orders),
                RefundOrder::class => new RefundOrderHandler($this->orders),
                RejectOrder::class => new RejectOrderHandler($this->orders),
                SendOrder::class => new SendOrderHandler($this->orders)
            ],
            $commandExtensions
        );
    }
}