<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\InMemory\Customer;

use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Checkout;
use Dumplie\Domain\Customer\Checkouts;
use Dumplie\Domain\Customer\Exception\CheckoutNotFoundException;

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
}