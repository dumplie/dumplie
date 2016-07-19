<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Application\Query\Result;

final class Product
{
    /**
     * @var string
     */
    private $sku;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var bool
     */
    private $isAvailable;

    /**
     * @param string $sku
     * @param float $price
     * @param string $currency
     * @param bool $isAvailable
     */
    public function __construct(string $sku, float $price, string $currency, bool $isAvailable)
    {
        $this->sku = $sku;
        $this->price = $price;
        $this->currency = $currency;
        $this->isAvailable = $isAvailable;
    }

    /**
     * @return string
     */
    public function sku() : string
    {
        return $this->sku;
    }

    /**
     * @return float
     */
    public function price() : float
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function currency() : string
    {
        return $this->currency;
    }

    /**
     * @return boolean
     */
    public function isAvailable() : bool
    {
        return $this->isAvailable;
    }
}