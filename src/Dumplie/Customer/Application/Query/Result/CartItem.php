<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Query\Result;

use Dumplie\Metadata\Metadata;

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
     * @var Metadata
     */
    private $metadata;

    /**
     * CartItem constructor.
     *
     * @param string $sku
     * @param int    $quantity
     * @param float  $price
     * @param string $currency
     */
    public function __construct(string $sku, int $quantity, float $price, string $currency, Metadata $metadata)
    {
        $this->sku = $sku;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->currency = $currency;
        $this->metadata = $metadata;
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

    /**
     * @return Metadata
     */
    public function metadata()
    {
        return $this->metadata;
    }
}
