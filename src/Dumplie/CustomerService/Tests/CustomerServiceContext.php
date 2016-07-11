<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Tests;

use Dumplie\CustomerService\Domain\OrderId;
use Dumplie\CustomerService\Domain\Orders;
use Dumplie\CustomerService\Domain\PaymentId;
use Dumplie\CustomerService\Domain\Payments;
use Dumplie\SharedKernel\Tests\Context\Context;

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