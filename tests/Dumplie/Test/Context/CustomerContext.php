<?php

declare (strict_types = 1);

namespace Dumplie\Test\Context;

use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Carts;
use Dumplie\Domain\Customer\Checkouts;
use Dumplie\Domain\Customer\Orders;
use Dumplie\Domain\Customer\Products;

interface CustomerContext extends Context
{
    /**
     * @return Carts
     */
    public function carts() : Carts;
    
    /**
     * @return Orders
     */
    public function orders() : Orders;

    /**
     * @return Checkouts
     */
    public function checkouts() : Checkouts;

    /**
     * @return Products
     */
    public function products() : Products;

    /**
     * @param $cartId
     *
     * @throws \Dumplie\Domain\Customer\Exception\CartNotFoundException
     * @throws \Dumplie\Domain\Customer\Exception\CheckoutAlreadyExistsException
     */
    public function checkout(CartId $cartId);

    /**
     * @param string $currency
     * @return CartId
     */
    public function createEmptyCart(string $currency = 'EUR') : CartId;

    /**
     * @param string $currency
     * @param array $skuCodes
     * @return CartId
     */
    public function createNewCartWithProducts(string $currency = 'EUR', array $skuCodes = []) : CartId;
}