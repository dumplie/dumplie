<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Customer;

use Dumplie\Application\Transaction\Factory;
use Dumplie\Domain\Customer\Address;
use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Carts;
use Dumplie\Domain\Customer\Checkout;
use Dumplie\Domain\Customer\Checkouts;
use Dumplie\Domain\Customer\Exception\CartNotFoundException;
use Dumplie\Domain\Customer\Exception\CheckoutAlreadyExistsException;
use Dumplie\Domain\Customer\Exception\InvalidArgumentException;
use Dumplie\Domain\SharedKernel\Exception\InvalidUUIDFormatException;

final class NewCheckoutHandler
{
    /**
     * @var Checkouts
     */
    private $checkouts;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var Carts
     */
    private $carts;

    /**
     * @param Checkouts $checkouts
     * @param Carts $carts
     * @param Factory $factory
     */
    public function __construct(Checkouts $checkouts, Carts $carts, Factory $factory)
    {
        $this->checkouts = $checkouts;
        $this->factory = $factory;
        $this->carts = $carts;
    }

    /**
     * @param NewCheckout $command
     * @throws CheckoutAlreadyExistsException
     * @throws InvalidArgumentException
     * @throws CartNotFoundException
     * @throws InvalidUUIDFormatException
     */
    public function handle(NewCheckout $command)
    {
        $cartId = new CartId($command->cartId());

        if (!$this->carts->exists($cartId)) {
            throw new CartNotFoundException;
        }

        $billingAddress = new Address(
            $command->name(),
            $command->street(),
            $command->postCode(),
            $command->city(),
            $command->countryIso2Code()
        );

        if ($this->checkouts->existsForCart($cartId)) {
            throw new CheckoutAlreadyExistsException;
        }

        $transaction = $this->factory->open();

        try {
            $this->checkouts->add(new Checkout($cartId, $billingAddress));
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
        }
    }
}