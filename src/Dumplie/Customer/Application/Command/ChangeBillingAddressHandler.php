<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Command;

use Dumplie\Customer\Application\Command\ChangeBillingAddress;
use Dumplie\Customer\Domain\Address;
use Dumplie\Customer\Domain\CartId;
use Dumplie\Customer\Domain\Checkouts;
use Dumplie\Customer\Domain\Exception\InvalidArgumentException;

final class ChangeBillingAddressHandler
{
    /**
     * @var Checkouts
     */
    private $checkouts;

    /**
     * @param Checkouts $checkouts
     */
    public function __construct(Checkouts $checkouts)
    {
        $this->checkouts = $checkouts;
    }

    /**
     * @param ChangeBillingAddress $command
     * @throws InvalidArgumentException
     */
    public function handle(ChangeBillingAddress $command)
    {
        $billing = new Address(
            $command->name(),
            $command->street(),
            $command->postCode(),
            $command->city(),
            $command->countryIso2Code()
        );

        $checkout = $this->checkouts->getForCart(new CartId($command->cartId()));
        $checkout->changeBillingAddress($billing);
    }
}