<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Customer;

use Dumplie\Application\Transaction\Factory;
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
     * @param CreateCart $command
     *
     * @throws \Exception
     */
    public function handle(CreateCart $command)
    {
        $transaction = $this->factory->open();

        try {
            $this->carts->add(new Cart(new CartId($command->uuid()), $command->currency()));
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}
