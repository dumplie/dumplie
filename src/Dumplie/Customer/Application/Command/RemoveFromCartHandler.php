<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Command;

use Dumplie\Customer\Application\Command\RemoveFromCart;
use Dumplie\Customer\Domain\CartId;
use Dumplie\Customer\Domain\Carts;
use Dumplie\SharedKernel\Domain\Product\SKU;

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
