<?php

namespace Dumplie\Test\Integration\Application\InMemory\Customer;

use Dumplie\Application\Command\Customer\ChangeBillingAddress;
use Dumplie\Application\Command\Customer\ChangeBillingAddressHandler;
use Dumplie\Application\Command\Customer\ChangeShippingAddress;
use Dumplie\Application\Command\Customer\ChangeShippingAddressHandler;
use Dumplie\Application\Command\Customer\NewCheckout;
use Dumplie\Application\Command\Customer\NewCheckoutHandler;
use Dumplie\Domain\Customer\Cart;
use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Carts;
use Dumplie\Domain\Customer\Checkouts;
use Dumplie\Infrastructure\InMemory\Customer\InMemoryCarts;
use Dumplie\Infrastructure\InMemory\Customer\InMemoryCheckouts;
use Dumplie\Infrastructure\InMemory\Transaction\Factory;

class CheckoutTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Checkouts
     */
    private $checkouts;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var Carts
     */
    private $carts;

    function setUp()
    {
        $this->checkouts = new InMemoryCheckouts();
        $this->carts = new InMemoryCarts();
        $this->factory = new Factory();
    }

    function test_new_checkout()
    {
        $cartId = CartId::generate();

        $this->carts->add(new Cart($cartId, 'EUR'));

        $command = new NewCheckout((string) $cartId, "Norbert Orzechowicz", "ul. FLorianska 1", "30-300", "Kraków", "PL");
        $handler = new NewCheckoutHandler($this->checkouts, $this->carts, $this->factory);

        $handler->handle($command);

        $this->assertTrue($this->checkouts->existsForCart($cartId));
    }

    function test_change_shipping_address()
    {
        $cartId = CartId::generate();

        $this->carts->add(new Cart($cartId, 'EUR'));

        $command = new NewCheckout((string) $cartId, "Norbert Orzechowicz", "ul. FLorianska 1", "30-300", "Kraków", "PL");
        $handler = new NewCheckoutHandler($this->checkouts, $this->carts, $this->factory);

        $handler->handle($command);

        $shippingAddressCommand = new ChangeShippingAddress((string) $cartId, "Lesze Prabucki", "ul. Rynek 2", "40-400", "Gdańsk", "PL");
        $shippingAddressHandler = new ChangeShippingAddressHandler($this->checkouts, $this->factory);

        $shippingAddressHandler->handle($shippingAddressCommand);

        $this->assertEquals(
            "Norbert Orzechowicz, 30-300 Kraków, ul. FLorianska 1, PL",
            (string) $this->checkouts->getForCart($cartId)->billingAddress()
        );
        $this->assertEquals(
            "Lesze Prabucki, 40-400 Gdańsk, ul. Rynek 2, PL",
            (string) $this->checkouts->getForCart($cartId)->shippingAddress()
        );
    }


    function test_change_billing_address()
    {
        $cartId = CartId::generate();

        $this->carts->add(new Cart($cartId, 'EUR'));

        $command = new NewCheckout((string) $cartId, "Norbert Orzechowicz", "ul. FLorianska 1", "30-300", "Kraków", "PL");
        $handler = new NewCheckoutHandler($this->checkouts, $this->carts, $this->factory);

        $handler->handle($command);

        $shippingAddressCommand = new ChangeBillingAddress((string) $cartId, "Lesze Prabucki", "ul. Rynek 2", "40-400", "Gdańsk", "PL");
        $shippingAddressHandler = new ChangeBillingAddressHandler($this->checkouts, $this->factory);

        $shippingAddressHandler->handle($shippingAddressCommand);

        $this->assertEquals(
            "Lesze Prabucki, 40-400 Gdańsk, ul. Rynek 2, PL",
            (string) $this->checkouts->getForCart($cartId)->billingAddress()
        );
    }
}
