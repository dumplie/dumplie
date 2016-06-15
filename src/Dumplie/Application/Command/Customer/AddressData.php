<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Customer;

trait AddressData
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
     * @return string
     */
    public function cartId() : string
    {
        return $this->cartId;
    }

    /**
     * @return string
     */
    public function name() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function street() : string
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function postCode() : string
    {
        return $this->postCode;
    }

    /**
     * @return string
     */
    public function city() : string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function countryIso2Code() : string
    {
        return $this->countryIso2Code;
    }
}