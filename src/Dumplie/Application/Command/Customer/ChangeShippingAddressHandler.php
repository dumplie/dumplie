<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Customer;

use Dumplie\Domain\Customer\Address;
use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Checkouts;
use Dumplie\Domain\Customer\Exception\InvalidArgumentException;

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