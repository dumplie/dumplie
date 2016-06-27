<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Customer;

use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Carts;
use Dumplie\Domain\SharedKernel\Product\SKU;

final class RemoveFromCartHandler
{
    /**
     * @var Carts
     */
    private $carts;

    /**
     * @param Carts   $carts
     */
    public function __construct(Carts $carts)
    {
        $this->carts = $carts;
    }

    /**
     * @param RemoveFromCart $command
     *
     * @throws \Exception
     */
    public function handle(RemoveFromCart $command)
    {
        $cart = $this->carts->getById(new CartId($command->cartId()));
        $cart->remove(new SKU($command->sku()));
    }
}
