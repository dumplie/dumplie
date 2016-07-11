<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Domain;

use Dumplie\Customer\Domain\Exception\EmptyCartException;
use Dumplie\Customer\Domain\Exception\ProductNotFoundException;

final class Checkout
{
    /**
     * @var CartId
     */
    private $cartId;

    /**
     * @var Address
     */
    private $billingAddress;

    /**
     * @var Address
     */
    private $shippingAddress;

    /**
     * @param CartId $cartId
     * @param Address $billingAddress
     */
    public function __construct(CartId $cartId, Address $billingAddress)
    {
        $this->cartId = $cartId;
        $this->billingAddress = $billingAddress;
        $this->shippingAddress = $billingAddress;
    }

    /**
     * @param Address $address
     */
    public function changeShippingAddress(Address $address)
    {
        $this->shippingAddress = $address;
    }

    /**
     * @param Address $address
     */
    public function changeBillingAddress(Address $address)
    {
        $this->billingAddress = $address;
    }

    /**
     * @return CartId
     */
    public function cartId() : CartId
    {
        return $this->cartId;
    }

    /**
     * @return Address
     */
    public function shippingAddress() : Address
    {
        return $this->shippingAddress;
    }

    /**
     * @return Address
     */
    public function billingAddress() : Address
    {
        return $this->billingAddress;
    }

    /**
     * @param OrderId $id
     * @param Products $products
     * @param Carts $carts
     * @return Order
     * @throws EmptyCartException
     * @throws ProductNotFoundException
     */
    public function placeOrder(OrderId $id, Products $products, Carts $carts) : Order
    {
        $cart = $carts->getById($this->cartId());

        if ($cart->isEmpty()) {
            throw new EmptyCartException;
        }
        $orderItems = [];
        foreach ($cart->items() as $item) {
            $product = $products->getBySku($item->sku());

            $orderItems[] = OrderItem::createFromProduct($product, $item->quantity());
        }

        return new Order($id, $orderItems, $this->billingAddress(), $this->shippingAddress());
    }
}