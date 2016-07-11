<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Query;

use Dumplie\SharedKernel\Application\Exception\Query\NotFoundException;
use Dumplie\Customer\Application\Query\Result\Cart;

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
