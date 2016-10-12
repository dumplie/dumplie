<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Application\Command;

use Dumplie\SharedKernel\Application\Command\Command;
use Dumplie\SharedKernel\Application\Command\CommandSerialize;
use Dumplie\SharedKernel\Domain\Money\Price;

final class CreateProduct implements Command
{
    use CommandSerialize;

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
     * @var int
     */
    private $precision;

    /**
     * CreateProduct constructor.
     *
     * @param string $sku
     * @param int $amount
     * @param string $currency
     * @param bool $isInStock
     * @param int $precision
     */
    public function __construct(
        string $sku,
        int $amount,
        string $currency,
        bool $isInStock,
        int $precision = Price::DEFAULT_PRECISION
    ) {
        $this->sku = $sku;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->isInStock = $isInStock;
        $this->precision = $precision;
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
    public function amount() : int
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

    /**
     * @return int
     */
    public function precision() : int
    {
        return $this->precision;
    }
}
