<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Query\Result;

use Dumplie\SharedKernel\Application\Exception\InvalidArgumentException;
use Dumplie\Customer\Application\Query\Result\CartItem;

final class Cart
{
    /**
     * @var string
     */
    private $currency;

    /**
     * @var CartItem[]
     */
    private $items;

    /**
     * @var int
     */
    private $totalQuantity;

    /**
     * @var float
     */
    private $totalPrice;

    /**
     * @param CartItem[] $items
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $currency, array $items = [])
    {
        $this->currency = $currency;
        $this->totalPrice = 0;
        $this->totalQuantity = 0;

        foreach ($items as $item) {
            if (!$item instanceof CartItem) {
                throw new InvalidArgumentException();
            }

            $this->totalQuantity += $item->quantity();
            $this->totalPrice += $item->price();
        }

        $this->items = $items;
    }

    /**
     * @return CartItem[]
     */
    public function items(): array
    {
        return $this->items;
    }

    /**
     * @return bool
     */
    public function isEmpty() : bool
    {
        return !count($this->items);
    }

    /**
     * @return int
     */
    public function totalQuantity(): int
    {
        return $this->totalQuantity;
    }

    /**
     * @return float
     */
    public function totalPrice() : float
    {
        return $this->totalPrice;
    }

    /**
     * @return string
     */
    public function currency() : string
    {
        return $this->currency;
    }
}
