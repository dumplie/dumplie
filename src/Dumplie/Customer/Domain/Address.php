<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Domain;

use Dumplie\Customer\Domain\Exception\InvalidArgumentException;

final class Address
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $postCode;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $countryIso2code;

    /**
     * @param string $name
     * @param string $street
     * @param string $postCode
     * @param string $city
     * @param string $countryIso2code
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $name,
        string $street,
        string $postCode,
        string $city,
        string $countryIso2code
    ) {
        if (empty($name)) {
            throw InvalidArgumentException::invalidAddress('', 'name');
        }

        if (empty($street)) {
            throw InvalidArgumentException::invalidAddress('', 'street');
        }

        if (empty($postCode)) {
            throw InvalidArgumentException::invalidAddress('', 'post code');
        }

        if (empty($city)) {
            throw InvalidArgumentException::invalidAddress('', 'city');
        }

        if (empty($countryIso2code)) {
            throw InvalidArgumentException::invalidAddress('', 'country ISO2 code');
        }

        if (mb_strlen($countryIso2code) !== 2) {
            throw InvalidArgumentException::invalidAddress($countryIso2code, 'country ISO2 code');
        }

        $this->name = $name;
        $this->street = $street;
        $this->postCode = $postCode;
        $this->city = $city;
        $this->countryIso2code = $countryIso2code;
    }

    public function __toString()
    {
        return sprintf(
            "%s, %s %s, %s, %s",
            $this->name,
            $this->postCode,
            $this->city,
            $this->street,
            $this->countryIso2code
        );
    }

}