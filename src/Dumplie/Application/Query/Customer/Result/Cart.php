<?php

declare (strict_types = 1);

namespace Dumplie\Application\Query\Customer\Result;

use Dumplie\Application\Exception\InvalidArgumentException;

final class Cart
{
    /**
     * @var CartItem[]
     */
    private $items;

    /**
     * @var float
     */
    private $totalPrice;

    /**
     * @param CartItem[] $items
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $items = [])
    {
        $this->totalPrice = 0;

        foreach ($items as $item) {
            if (!$item instanceof CartItem) {
                throw new InvalidArgumentException();
            }

            $this->totalPrice += $item->price();
        }

        $this->items = $items;
    }

    /**
     * @return bool
     */
    public function isEmpty() : bool
    {
        return !count($this->items);
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
        return $this->isEmpty() ? null : $this->items[0]->currency();
    }
}
