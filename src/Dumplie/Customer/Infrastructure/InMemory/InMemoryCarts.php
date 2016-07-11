<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Infrastructure\InMemory;

use Dumplie\Customer\Domain\Cart;
use Dumplie\Customer\Domain\CartId;
use Dumplie\Customer\Domain\Carts;
use Dumplie\Customer\Domain\Exception\CartNotFoundException;

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
     *
     * @throws CartNotFoundException
     */
    public function remove(CartId $cartId)
    {
        if (!$this->exists($cartId)) {
            throw CartNotFoundException::byId($cartId);
        }

        unset($this->carts[(string) $cartId]);
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
