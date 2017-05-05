<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Query\Result;

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
    private $countryIso2Code;

    /**
     * @param string $name
     * @param string $street
     * @param string $postCode
     * @param string $city
     * @param string $countryIso2code
     */
    public function __construct(
        string $name,
        string $street,
        string $postCode,
        string $city,
        string $countryIso2code
    ) {
        $this->name = $name;
        $this->street = $street;
        $this->postCode = $postCode;
        $this->city = $city;
        $this->countryIso2Code = $countryIso2code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getPostCode(): string
    {
        return $this->postCode;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryIso2Code;
    }

    public function __toString()
    {
        return sprintf(
            "%s, %s %s, %s, %s",
            $this->name,
            $this->postCode,
            $this->city,
            $this->street,
            $this->countryIso2Code
        );
    }

}