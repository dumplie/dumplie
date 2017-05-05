<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Query\Result;


use Dumplie\Customer\Domain\CartId;

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
     * @param Address $shippingAddress
     */
    public function __construct(CartId $cartId, Address $billingAddress, Address $shippingAddress)
    {
        $this->cartId = $cartId;
        $this->billingAddress = $billingAddress;
        $this->shippingAddress = $shippingAddress;
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
}