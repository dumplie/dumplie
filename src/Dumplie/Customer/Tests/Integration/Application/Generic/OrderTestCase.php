<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Tests\Integration\Application\Generic;

use Dumplie\Customer\Application\Command\PlaceOrder;
use Dumplie\SharedKernel\Application\EventLog;
use Dumplie\SharedKernel\Application\Transaction\Factory;
use Dumplie\Customer\Domain\Exception\OrderAlreadyExistsException;
use Dumplie\Customer\Domain\OrderId;
use Dumplie\Customer\Tests\CustomerContext;

abstract class OrderTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Factory
     */
    protected $transactionFactory;

    /**
     * @var CustomerContext
     */
    protected $customerContext;

    /**
     * @var EventLog
     */
    protected $eventLog;

    abstract public function clear();

    function test_placing_new_order()
    {
        $cartId = $this->customerContext->createNewCartWithProducts('EUR', ['SKU_1', 'SKU_2']);
        $this->customerContext->checkout($cartId);
        $orderId = OrderId::generate();
        $placeOrderCommand = new PlaceOrder((string) $cartId, (string) $orderId);

        $this->customerContext->commandBus()->handle($placeOrderCommand);

        $this->clear();

        $this->assertFalse($this->customerContext->carts()->exists($cartId));
        $this->assertFalse($this->customerContext->checkouts()->existsForCart($cartId));
        $this->assertTrue($this->customerContext->orders()->exists($orderId));
    }

    function test_placing_order_with_the_same_id_twice()
    {
        $this->expectException(OrderAlreadyExistsException::class);

        $cartId = $this->customerContext->createNewCartWithProducts('EUR', ['SKU_1', 'SKU_2']);
        $this->customerContext->checkout($cartId);
        $orderId = OrderId::generate();
        $placeOrderCommand = new PlaceOrder((string) $cartId, (string) $orderId);

        $this->customerContext->commandBus()->handle($placeOrderCommand);
        $this->customerContext->commandBus()->handle($placeOrderCommand);
    }
}