<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Inventory;

final class CreateProduct
{
    /**
     * @var string
     */
    private $sku;
 
    /**
     * @var int
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var bool
     */
    private $isInStock;

    /**
     * CreateProduct constructor.
     *
     * @param string $sku
     * @param int    $amount
     * @param string $currency
     * @param bool   $isInStock
     */
    public function __construct(
        string $sku,
        int $amount,
        string $currency,
        bool $isInStock
    ) {
        $this->sku = $sku;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->isInStock = $isInStock;
    }

    /**
     * @return string
     */
    public function sku(): string
    {
        return $this->sku;
    }

    /**
     * @return int
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function currency(): string
    {
        return $this->currency;
    }

    /**
     * @return bool
     */
    public function isInStock(): bool
    {
        return $this->isInStock;
    }
}
