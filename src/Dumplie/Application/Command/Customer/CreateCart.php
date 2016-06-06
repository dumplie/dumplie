<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Customer;

final class CreateCart
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $currency;

    /**
     * @param string $uuid
     * @param string $currency
     */
    public function __construct(string $uuid, string $currency)
    {
        $this->currency = $currency;
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function currency() : string
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function uuid() : string
    {
        return $this->uuid;
    }
}
