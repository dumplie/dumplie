<?php

declare (strict_types = 1);

namespace Dumplie\Application\Query\Customer;

use Dumplie\Application\Exception\Query\NotFoundException;
use Dumplie\Application\Query\Customer\Result\Cart;

interface CartQuery
{
    /**
     * @param string $cartId
     *
     * @throws NotFoundException
     *
     * @return Cart
     */
    public function getById(string $cartId) : Cart;

    /**
     * @param string $cartId
     *
     * @return bool
     */
    public function doesCartWithIdExist(string $cartId) : bool;
}
