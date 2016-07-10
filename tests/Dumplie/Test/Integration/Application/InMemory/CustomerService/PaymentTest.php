<?php

namespace Dumplie\Test\Integration\Application\InMemory\CustomerService;

use Dumplie\Application\Command\CustomerService\CreatePayment;
use Dumplie\Application\Command\CustomerService\CreatePaymentHandler;
use Dumplie\Application\Command\CustomerService\PayPayment;
use Dumplie\Application\Command\CustomerService\PayPaymentHandler;
use Dumplie\Application\Command\CustomerService\RejectPayment;
use Dumplie\Application\Command\CustomerService\RejectPaymentHandler;
use Dumplie\Domain\CustomerService\Order;
use Dumplie\Domain\CustomerService\OrderId;
use Dumplie\Domain\CustomerService\Payment;
use Dumplie\Domain\CustomerService\Payments;
use Dumplie\Infrastructure\InMemory\CustomerService\InMemoryOrders;
use Dumplie\Infrastructure\InMemory\CustomerService\InMemoryPayments;
use Dumplie\Infrastructure\InMemory\InMemoryEventLog;
use Dumplie\Test\Context\Memory\InMemoryCustomerServiceContext;
use Dumplie\Test\Context\Tactician\TacticianFactory;
use Dumplie\Test\Integration\Application\Generic\CustomerService\PaymentTestCase;

class PaymentTest extends PaymentTestCase
{
    function setUp()
    {
        $this->customerServiceContext = new InMemoryCustomerServiceContext(new InMemoryEventLog(), new TacticianFactory());
    }

    protected function clear()
    {
    }
}
