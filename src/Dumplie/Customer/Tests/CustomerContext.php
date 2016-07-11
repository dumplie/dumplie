<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Tests;

use Dumplie\Customer\Domain\CartId;
use Dumplie\Customer\Domain\Carts;
use Dumplie\Customer\Domain\Checkouts;
use Dumplie\Customer\Domain\Orders;
use Dumplie\Customer\Domain\Products;
use Dumplie\SharedKernel\Tests\Context\Context;

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
     * @throws \Dumplie\Customer\Domain\Exception\CartNotFoundException
     * @throws \Dumplie\Customer\Domain\Exception\CheckoutAlreadyExistsException
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