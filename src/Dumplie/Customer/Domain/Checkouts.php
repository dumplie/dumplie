<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Domain;

use Dumplie\Customer\Domain\Exception\CheckoutNotFoundException;

interface Checkouts
{
    /**
     * @param Checkout $checkout
     */
    public function add(Checkout $checkout);

    /**
     * @param CartId $cartId
     * @return bool
     */
    public function existsForCart(CartId $cartId) : bool ;

    /**
     * @param CartId $cartId
     * @return Checkout
     *
     * @throws CheckoutNotFoundException
     */
    public function getForCart(CartId $cartId) : Checkout;

    /**
     * @param CartId $cartId
     */
    public function removeForCart(CartId $cartId);
}