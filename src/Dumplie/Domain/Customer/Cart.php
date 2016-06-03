<?php

declare (strict_types = 1);

namespace Dumplie\Domain\Customer;

use Dumplie\Domain\Customer\Exception\ProductNotAvailableException;
use Dumplie\Domain\SharedKernel\Exception\InvalidCurrencyException;
use Dumplie\Domain\SharedKernel\Money\Price;

final class Cart
{
    /**
     * @var CartId
     */
    private $id;

    /**
     * @var CartItem[]
     */
    private $items;

    public function __construct()
    {
        $this->id = CartId::generate();
        $this->items = [];
    }

    /**
     * @return CartId
     */
    public function id() : CartId
    {
        return $this->id;
    }

    /**
     * @param Product $product
     * @param int     $quantity
     *
     * @throws \Dumplie\Domain\SharedKernel\Exception\InvalidCurrencyException
     * @throws \Dumplie\Domain\Customer\Exception\ProductNotAvailableException
     */
    public function add(Product $product, int $quantity)
    {
        if (!$product->isAvailable()) {
            throw new ProductNotAvailableException($product);
        }

        foreach ($this->items as $item) {
            if (!$item->totalPrice()->hasSameCurrency($product->price())) {
                throw new InvalidCurrencyException($item->totalPrice()->currency(), $product->price()->currency());
            }
        }

        $this->items[(string) $product->sku()] = new CartItem($product, $quantity);
    }

    /**
     * @return array|CartItem[]
     */
    public function items() : array
    {
        return $this->items;
    }

    /**
     * @return Price
     */
    public function totalPrice() : Price
    {
        $orderTotalPrice = null;

        foreach ($this->items as $item) {
            $orderTotalPrice = is_null($orderTotalPrice) ? $item->totalPrice() : $orderTotalPrice->add($item->totalPrice());
        }

        return $orderTotalPrice;
    }

    /**
     * @return bool
     */
    public function isEmpty() : bool
    {
        return !count($this->items);
    }
}
