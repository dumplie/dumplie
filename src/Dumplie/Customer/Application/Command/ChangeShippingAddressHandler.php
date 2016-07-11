<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Command;

use Dumplie\Customer\Application\Command\ChangeShippingAddress;
use Dumplie\Customer\Domain\Address;
use Dumplie\Customer\Domain\CartId;
use Dumplie\Customer\Domain\Checkouts;
use Dumplie\Customer\Domain\Exception\InvalidArgumentException;

final class ChangeShippingAddressHandler
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
     * @param ChangeShippingAddress $command
     * @throws InvalidArgumentException
     */
    public function handle(ChangeShippingAddress $command)
    {
        $shippingAddress = new Address(
            $command->name(),
            $command->street(),
            $command->postCode(),
            $command->city(),
            $command->countryIso2Code()
        );

        $checkout = $this->checkouts->getForCart(new CartId($command->cartId()));
        $checkout->changeShippingAddress($shippingAddress);
    }
}