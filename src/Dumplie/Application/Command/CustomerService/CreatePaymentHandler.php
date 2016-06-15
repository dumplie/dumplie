<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\CustomerService;

use Dumplie\Application\Transaction\Factory;
use Dumplie\Domain\CustomerService\OrderId;
use Dumplie\Domain\CustomerService\Orders;
use Dumplie\Domain\CustomerService\Payment;
use Dumplie\Domain\CustomerService\Payments;

final class CreatePaymentHandler
{
    /**
     * @var Orders
     */
    private $orders;

    /**
     * @var Payments
     */
    private $payments;
    /**
     * @var Factory
     */
    private $factory;

    /**
     * PayPaymentHandler constructor.
     *
     * @param Orders   $orders
     * @param Payments $payments
     * @param Factory  $factory
     */
    public function __construct(Orders $orders, Payments $payments, Factory $factory)
    {
        $this->orders = $orders;
        $this->payments = $payments;
        $this->factory = $factory;
    }

    /**
     * @param CreatePayment $command
     *
     * @throws \Exception
     */
    public function handle(CreatePayment $command)
    {
        $transaction = $this->factory->open();

        try {
            $order = $this->orders->getById(new OrderId($command->orderId()));
            $this->payments->add(new Payment($order));
            
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}
