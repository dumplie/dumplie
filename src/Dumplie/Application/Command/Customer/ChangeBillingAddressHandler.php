<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Customer;

use Dumplie\Application\Transaction\Factory;
use Dumplie\Domain\Customer\Address;
use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Checkouts;
use Dumplie\Domain\Customer\Exception\InvalidArgumentException;

final class ChangeBillingAddressHandler
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
     * @param Checkouts $checkouts
     */
    public function __construct(Checkouts $checkouts, Factory $factory)
    {
        $this->checkouts = $checkouts;
        $this->factory = $factory;
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

        $transaction = $this->factory->open();

        try {
            $checkout = $this->checkouts->getForCart(new CartId($command->cartId()));
            $checkout->changeBillingAddress($billing);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
        }
    }
}