<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Tests\Integration\Application\Generic;

use Dumplie\Customer\Application\Command\ChangeBillingAddress;
use Dumplie\Customer\Application\Command\ChangeShippingAddress;
use Dumplie\Customer\Application\Command\NewCheckout;
use Dumplie\SharedKernel\Application\Transaction\Factory;
use Dumplie\Customer\Tests\CustomerContext;

abstract class CheckoutTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Factory
     */
    protected $transactionFactory;

    /**
     * @var CustomerContext
     */
    protected $customerContext;

    abstract public function clear();

    function test_new_checkout()
    {
        $cartId = $this->customerContext->createEmptyCart();
        $command = new NewCheckout((string) $cartId, "Norbert Orzechowicz", "ul. FLorianska 1", "30-300", "Kraków", "PL");

        $this->customerContext->commandBus()->handle($command);

        $this->clear();
        $this->assertTrue($this->customerContext->checkouts()->existsForCart($cartId));
    }

    function test_change_shipping_address()
    {
        $cartId = $this->customerContext->createEmptyCart();
        $command = new NewCheckout((string) $cartId, "Norbert Orzechowicz", "ul. FLorianska 1", "30-300", "Kraków", "PL");
        $this->customerContext->commandBus()->handle($command);
        $shippingAddressCommand = new ChangeShippingAddress((string) $cartId, "Lesze Prabucki", "ul. Rynek 2", "40-400", "Gdańsk", "PL");

        $this->customerContext->commandBus()->handle($shippingAddressCommand);

        $this->clear();
        $this->assertEquals(
            "Norbert Orzechowicz, 30-300 Kraków, ul. FLorianska 1, PL",
            (string) $this->customerContext->checkouts()->getForCart($cartId)->billingAddress()
        );
        $this->assertEquals(
            "Lesze Prabucki, 40-400 Gdańsk, ul. Rynek 2, PL",
            (string) $this->customerContext->checkouts()->getForCart($cartId)->shippingAddress()
        );
    }

    function test_change_billing_address()
    {
        $cartId = $this->customerContext->createEmptyCart();
        $command = new NewCheckout((string) $cartId, "Norbert Orzechowicz", "ul. FLorianska 1", "30-300", "Kraków", "PL");
        $this->customerContext->commandBus()->handle($command);


        $changeBillingAddress = new ChangeBillingAddress((string) $cartId, "Lesze Prabucki", "ul. Rynek 2", "40-400", "Gdańsk", "PL");
        $this->customerContext->commandBus()->handle($changeBillingAddress);

        $this->clear();
        $this->assertEquals(
            "Lesze Prabucki, 40-400 Gdańsk, ul. Rynek 2, PL",
            (string) $this->customerContext->checkouts()->getForCart($cartId)->billingAddress()
        );
    }
}