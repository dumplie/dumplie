<?php

declare (strict_types = 1);

namespace Dumplie\Test\Integration\Application\Generic\Customer;

use Dumplie\Application\Command\Customer\PlaceOrder;
use Dumplie\Application\EventLog;
use Dumplie\Application\Transaction\Factory;
use Dumplie\Domain\Customer\Exception\OrderAlreadyExistsException;
use Dumplie\Domain\Customer\OrderId;
use Dumplie\Test\Context\CustomerContext;

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