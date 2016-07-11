<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Tests;

use Dumplie\CustomerService\Application\Command\AcceptOrder;
use Dumplie\CustomerService\Application\Command\AcceptOrderHandler;
use Dumplie\CustomerService\Application\Command\CreatePayment;
use Dumplie\CustomerService\Application\Command\CreatePaymentHandler;
use Dumplie\CustomerService\Application\Command\PayPayment;
use Dumplie\CustomerService\Application\Command\PayPaymentHandler;
use Dumplie\CustomerService\Application\Command\PrepareOrder;
use Dumplie\CustomerService\Application\Command\PrepareOrderHandler;
use Dumplie\CustomerService\Application\Command\RefundOrder;
use Dumplie\CustomerService\Application\Command\RefundOrderHandler;
use Dumplie\CustomerService\Application\Command\RejectOrder;
use Dumplie\CustomerService\Application\Command\RejectOrderHandler;
use Dumplie\CustomerService\Application\Command\RejectPayment;
use Dumplie\CustomerService\Application\Command\RejectPaymentHandler;
use Dumplie\CustomerService\Application\Command\SendOrder;
use Dumplie\CustomerService\Application\Command\SendOrderHandler;
use Dumplie\SharedKernel\Application\CommandBus;
use Dumplie\SharedKernel\Application\EventLog;
use Dumplie\SharedKernel\Application\Transaction\Factory;
use Dumplie\CustomerService\Domain\OrderId;
use Dumplie\CustomerService\Domain\Orders;
use Dumplie\CustomerService\Domain\PaymentId;
use Dumplie\CustomerService\Domain\Payments;
use Dumplie\Customer\Domain\Event\CustomerPlacedOrder;
use Dumplie\SharedKernel\Tests\Context\CommandBusFactory;

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