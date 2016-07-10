<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\CustomerService;

use Dumplie\Domain\CustomerService\Exception\OrderNotFoundException;
use Dumplie\Domain\CustomerService\OrderId;
use Dumplie\Domain\CustomerService\Orders;
use Dumplie\Domain\CustomerService\Payment;
use Dumplie\Domain\CustomerService\PaymentId;
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
