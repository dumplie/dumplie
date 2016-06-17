<?php

namespace Spec\Dumplie\Domain\Customer;

use Dumplie\Domain\Customer\Address;
use Dumplie\Domain\Customer\Cart;
use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Carts;
use Dumplie\Domain\Customer\Exception\EmptyCartException;
use Dumplie\Domain\Customer\Order;
use Dumplie\Domain\Customer\OrderId;
use Dumplie\Domain\Customer\Product;
use Dumplie\Domain\Customer\Products;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CheckoutSpec extends ObjectBehavior
{
    function let()
    {
        $billingAddress = new Address('Norbert Orzechowicz', 'ul. Floriańska 15', '30-300', 'Kraków', 'PL');

        $this->beConstructedWith(CartId::generate(), $billingAddress);
    }

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

    function it_can_be_used_to_place_order(Products $products, Carts $carts)
    {
        $product = new Product(new SKU("SKU"), Price::EUR(1000), true);
        $cart = new Cart(CartId::generate(), 'EUR');
        $cart->add($product, 1);
        $carts->getById(Argument::type(CartId::class))->willReturn($cart);

        $products->getBySku(Argument::type(SKU::class))->willReturn($product);

        $order = $this->placeOrder(OrderId::generate(), $products, $carts);

        $order->shouldBeAnInstanceOf(Order::class);
    }

    function it_throws_exception_when_cart_is_empty(Products $products, Carts $carts)
    {
        $cart = new Cart(CartId::generate(), 'EUR');
        $carts->getById(Argument::type(CartId::class))->willReturn($cart);

        $this->shouldThrow(EmptyCartException::class)
            ->during('placeOrder', [OrderId::generate(), $products, $carts]);
    }
}
