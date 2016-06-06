<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Customer;

use Dumplie\Domain\Customer\Cart;
use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Carts;

final class CreateCartHandler
{
    /**
     * @var Carts
     */
    private $carts;

    /**
     * @param Carts $carts
     */
    public function __construct(Carts $carts)
    {
        $this->carts = $carts;
    }

    /**
     * @param CreateCart $command
     */
    public function handle(CreateCart $command)
    {
        $this->carts->add(new Cart(new CartId($command->uuid()), $command->currency()));
    }
}
