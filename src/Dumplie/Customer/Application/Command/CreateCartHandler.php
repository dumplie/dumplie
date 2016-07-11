<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Command;

use Dumplie\Customer\Application\Command\CreateCart;
use Dumplie\Customer\Domain\Cart;
use Dumplie\Customer\Domain\CartId;
use Dumplie\Customer\Domain\Carts;

final class CreateCartHandler
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
     * @param CreateCart $command
     *
     * @throws \Exception
     */
    public function handle(CreateCart $command)
    {
        $this->carts->add(new Cart(new CartId($command->uuid()), $command->currency()));
    }
}
