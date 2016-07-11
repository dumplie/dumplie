<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Command;

use Dumplie\Customer\Application\Command\NewCheckout;
use Dumplie\Customer\Domain\Address;
use Dumplie\Customer\Domain\CartId;
use Dumplie\Customer\Domain\Carts;
use Dumplie\Customer\Domain\Checkout;
use Dumplie\Customer\Domain\Checkouts;
use Dumplie\Customer\Domain\Exception\CartNotFoundException;
use Dumplie\Customer\Domain\Exception\CheckoutAlreadyExistsException;
use Dumplie\Customer\Domain\Exception\InvalidArgumentException;
use Dumplie\SharedKernel\Domain\Exception\InvalidUUIDFormatException;

final class NewCheckoutHandler
{
    /**
     * @var Checkouts
     */
    private $checkouts;

    /**
     * @var Carts
     */
    private $carts;

    /**
     * @param Checkouts $checkouts
     * @param Carts $carts
     */
    public function __construct(Checkouts $checkouts, Carts $carts)
    {
        $this->checkouts = $checkouts;
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

        $this->checkouts->add(new Checkout($cartId, $billingAddress));
    }
}