<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\InMemory\Customer;

use Dumplie\Domain\Customer\Cart;
use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Carts;
use Dumplie\Domain\Customer\Exception\CartNotFoundException;

final class InMemoryCarts implements Carts
{
    /**
     * @var array
     */
    private $carts;

    /**
     * @param array|Cart[] $carts
     */
    public function __construct(array $carts = [])
    {
        $this->carts = [];

        foreach ($carts as $cart) {
            $this->add($cart);
        }
    }

    /**
     * @param CartId $cartId
     *
     * @return Cart
     *
     * @throws CartNotFoundException
     */
    public function getById(CartId $cartId) : Cart
    {
        if (!array_key_exists((string) $cartId, $this->carts)) {
            throw CartNotFoundException::byId($cartId);
        }

        return $this->carts[(string) $cartId];
    }

    /**
     * @param Cart $cart
     */
    public function add(Cart $cart)
    {
        $this->carts[(string) $cart->id()] = $cart;
    }

    /**
     * @param CartId $cartId
     * @return bool
     */
    public function exists(CartId $cartId) : bool
    {
        return array_key_exists((string) $cartId, $this->carts);
    }
}
