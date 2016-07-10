<?php

declare (strict_types = 1);

namespace Dumplie\Test\Context;

use Dumplie\Domain\CustomerService\OrderId;
use Dumplie\Domain\CustomerService\Orders;
use Dumplie\Domain\CustomerService\PaymentId;
use Dumplie\Domain\CustomerService\Payments;

interface CustomerServiceContext extends Context
{
    /**
     * @return Orders
     */
    public function orders() : Orders;

    /**
     * @return Payments
     */
    public function payments() : Payments;

    /**
     * @param string $orderId
     */
    public function customerPlacedOrder(string $orderId);

    /**
     * @param OrderId $orderId
     * @return PaymentId
     */
    public function createPaymentFor(OrderId $orderId) : PaymentId;
}