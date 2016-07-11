<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Command;

use Dumplie\SharedKernel\Application\Command\Command;
use Dumplie\SharedKernel\Application\Command\CommandSerialize;
use Dumplie\Customer\Application\Command\AddressData;

final class ChangeBillingAddress implements Command
{
    use CommandSerialize;
    use AddressData;

    /**
     * @var string
     */
    private $cartId;

    /**
     * @param string $cartId
     * @param string $name
     * @param string $street
     * @param string $postCode
     * @param string $city
     * @param string $countryIso2Code
     */
    public function __construct(string $cartId, string $name, string $street, string $postCode, string $city, string $countryIso2Code)
    {
        $this->cartId = $cartId;
        $this->name = $name;
        $this->street = $street;
        $this->postCode = $postCode;
        $this->city = $city;
        $this->countryIso2Code = $countryIso2Code;
    }

    /**
     * @return string
     */
    public function cartId() : string
    {
        return $this->cartId;
    }
}