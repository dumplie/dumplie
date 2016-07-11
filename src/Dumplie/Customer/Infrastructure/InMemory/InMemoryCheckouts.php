<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Infrastructure\InMemory;

use Dumplie\Customer\Domain\CartId;
use Dumplie\Customer\Domain\Checkout;
use Dumplie\Customer\Domain\Checkouts;
use Dumplie\Customer\Domain\Exception\CheckoutNotFoundException;

final class InMemoryCheckouts implements Checkouts
{
    /**
     * @var Checkout[]
     */
    private $checkouts;

    /**
     * @param array $checkouts
     */
    public function __construct(array $checkouts = [])
    {
        $this->checkouts = [];

        foreach ($checkouts as $checkout) {
            $this->add($checkout);
        }
    }

    public function add(Checkout $checkout)
    {
        $this->checkouts[(string) $checkout->cartId()] = $checkout;
    }

    /**
     * @param CartId $cartId
     * @return bool
     */
    public function existsForCart(CartId $cartId) : bool
    {
        return array_key_exists((string) $cartId, $this->checkouts);
    }

    /**
     * @param CartId $cartId
     * @return Checkout
     *
     * @throws CheckoutNotFoundException
     */
    public function getForCart(CartId $cartId) : Checkout
    {
        if (!$this->existsForCart($cartId)) {
            throw new CheckoutNotFoundException;
        }

        return $this->checkouts[(string) $cartId];
    }

    /**
     * @param CartId $cartId
     *
     * @throws CheckoutNotFoundException
     */
    public function removeForCart(CartId $cartId)
    {
        if (!$this->existsForCart($cartId)) {
            throw new CheckoutNotFoundException;
        }

        unset($this->checkouts[(string) $cartId]);
    }
}