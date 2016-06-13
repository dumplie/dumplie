<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Customer;

use Dumplie\Application\Transaction\Factory;
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
     * @var Factory
     */
    private $factory;

    /**
     * @param Carts   $carts
     * @param Factory $factory
     */
    public function __construct(Carts $carts, Factory $factory)
    {
        $this->carts = $carts;
        $this->factory = $factory;
    }

    /**
     * @param RemoveFromCart $command
     *
     * @throws \Exception
     */
    public function handle(RemoveFromCart $command)
    {
        $transaction = $this->factory->open();

        try {
            $cart = $this->carts->getById(new CartId($command->cartId()));
            $cart->remove(new SKU($command->sku()));

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}
