<?php

namespace Spec\Dumplie\Domain\Customer;

use Dumplie\Domain\Customer\Address;
use Dumplie\Domain\Customer\CartId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CheckoutSpec extends ObjectBehavior
{
    function it_use_billing_address_for_shipping_by_default()
    {
        $billingAddress = new Address('Norbert Orzechowicz', 'ul. Floriańska 15', '30-300', 'Kraków', 'PL');

        $this->beConstructedWith(CartId::generate(), $billingAddress);

        $this->billingAddress()->shouldReturn($billingAddress);
        $this->shippingAddress()->shouldReturn($billingAddress);
    }

    function it_can_have_different_shipping_and_billing_addresses()
    {
        $billingAddress = new Address('Norbert Orzechowicz', 'ul. Floriańska 15', '30-300', 'Kraków', 'PL');
        $shippingAddress = new Address('Leszek Prabucki', 'ul. Rynek 2', '40-100', 'Gdańsk', 'PL');

        $this->beConstructedWith(CartId::generate(), $billingAddress);

        $this->changeShippingAddress($shippingAddress);

        $this->billingAddress()->shouldReturn($billingAddress);
        $this->shippingAddress()->shouldReturn($shippingAddress);
    }
}
