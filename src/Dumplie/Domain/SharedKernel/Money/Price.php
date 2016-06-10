<?php

declare (strict_types = 1);

namespace Dumplie\Domain\SharedKernel\Money;

use Dumplie\Domain\SharedKernel\Exception\DifferentPricePrecisionException;
use Dumplie\Domain\SharedKernel\Exception\InvalidArgumentException;
use Dumplie\Domain\SharedKernel\Exception\InvalidCurrencyException;

final class Price
{
    const DEFAULT_PRECISION = 100;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var int
     */
    private $precision;

    /**
     * @param int    $amount    Amount, expressed in the smallest units of $currency (eg cents)
     * @param string $currency
     * @param int    $precision used to calculate float value, 10000 cents / 100 = 100.00
     *
     * @throws InvalidArgumentException
     */
    public function __construct(int $amount, string $currency, int $precision = self::DEFAULT_PRECISION)
    {
        if ($amount < 0) {
            throw InvalidArgumentException::negativePriceAmount($amount);
        }

        if ($precision < 0) {
            throw InvalidArgumentException::negativePricePrecision($precision);
        }

        if (!Currencies::isValid($currency)) {
            throw InvalidArgumentException::invalidCurrency($currency);
        }

        $this->amount = $amount;
        $this->currency = $currency;
        $this->precision = $precision;
    }

    /**
     * @param $method
     * @param $arguments
     *
     * @return Price
     */
    public static function __callStatic($method, $arguments) : Price
    {
        return new self($arguments[0], $method, isset($arguments[1]) ? $arguments[1] : self::DEFAULT_PRECISION);
    }

    /**
     * @param int    $value
     * @param string $currency
     *
     * @return Price
     */
    public static function fromInt(int $value, string $currency) : Price
    {
        return new self($value * self::DEFAULT_PRECISION, $currency);
    }

    /**
     * @return float
     */
    public function floatValue() : float
    {
        return $this->amount / $this->precision;
    }

    /**
     * @return string
     */
    public function currency() : string
    {
        return mb_strtoupper($this->currency);
    }

    /**
     * @param Price $price
     *
     * @return bool
     */
    public function hasSameCurrency(Price $price) : bool
    {
        return $price->currency() === $this->currency();
    }

    /**
     * @param string $currency
     *
     * @return bool
     */
    public function hasCurrency(string $currency) : bool
    {
        return $this->currency() === mb_strtoupper($currency);
    }

    /**
     * @param Price $addend
     *
     * @return Price
     *
     * @throws DifferentPricePrecisionException
     * @throws InvalidCurrencyException
     */
    public function add(Price $addend)
    {
        if (!$this->hasSameCurrency($addend)) {
            throw new InvalidCurrencyException($this->currency(), $addend->currency());
        }

        if ($this->precision !== $addend->precision) {
            throw new DifferentPricePrecisionException();
        }

        return new self($this->amount + $addend->amount, $this->currency(), $this->precision);
    }

    /**
     * @param int $multiplier
     *
     * @return Price
     */
    public function multiply(int $multiplier) : Price
    {
        return new self($this->amount * $multiplier, $this->currency(), $this->precision);
    }
}
