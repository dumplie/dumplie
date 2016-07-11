<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Application\Command;

use Dumplie\CustomerService\Domain\Exception\OrderNotFoundException;
use Dumplie\CustomerService\Domain\OrderId;
use Dumplie\CustomerService\Domain\Orders;
use Dumplie\CustomerService\Domain\Payment;
use Dumplie\CustomerService\Domain\PaymentId;
use Dumplie\CustomerService\Domain\Payments;

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
     * @param Orders   $orders
     * @param Payments $payments
     */
    public function __construct(Orders $orders, Payments $payments)
    {
        $this->orders = $orders;
        $this->payments = $payments;
    }

    /**
     * @param CreatePayment $command
     * @throws OrderNotFoundException
     */
    public function handle(CreatePayment $command)
    {
        $order = $this->orders->getById(new OrderId($command->orderId()));
        
        $this->payments->add(new Payment(new PaymentId($command->paymentId()), $order));
    }
}
