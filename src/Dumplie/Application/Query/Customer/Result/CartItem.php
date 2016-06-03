<?php

declare (strict_types = 1);

namespace Dumplie\Application\Query\Customer\Result;

final class CartItem
{
    /**
     * @var string
     */
    private $sku;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $currency;

    /**
     * CartItem constructor.
     *
     * @param string $sku
     * @param int    $quantity
     * @param float  $price
     * @param string $currency
     */
    public function __construct(string $sku, int $quantity, float $price, string $currency)
    {
        $this->sku = $sku;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function sku() : string
    {
        return $this->sku;
    }

    /**
     * @return int
     */
    public function quantity() : int
    {
        return $this->quantity;
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
}
