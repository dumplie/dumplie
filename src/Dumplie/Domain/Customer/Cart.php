<?php

declare (strict_types = 1);

namespace Dumplie\Domain\Customer;

use Dumplie\Domain\Customer\Exception\InvalidArgumentException;
use Dumplie\Domain\Customer\Exception\ProductNotAvailableException;
use Dumplie\Domain\Customer\Exception\ProductNotInCartException;
use Dumplie\Domain\SharedKernel\Exception\InvalidCurrencyException;
use Dumplie\Domain\SharedKernel\Money\Currencies;
use Dumplie\Domain\SharedKernel\Product\SKU;

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

    /**
     * @var string
     */
    private $currency;

    /**
     * @param CartId $cartId
     * @param string $currency
     *
     * @throws InvalidArgumentException
     */
    public function __construct(CartId $cartId, string $currency)
    {
        if (!Currencies::isValid($currency)) {
            throw InvalidArgumentException::invalidCurrency($currency);
        }

        $this->id = $cartId;
        $this->items = [];
        $this->currency = $currency;
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

        if (!$product->price()->hasCurrency($this->currency)) {
            throw new InvalidCurrencyException($this->currency, $product->price()->currency());
        }

        $this->items[(string) $product->sku()] = new CartItem($product->sku(), $quantity);
    }

    /**
     * @param SKU $sku
     *
     * @throws ProductNotInCartException
     */
    public function remove(SKU $sku)
    {
        if (!array_key_exists((string) $sku, $this->items)) {
            throw new ProductNotInCartException();
        }

        unset($this->items[(string) $sku]);
    }

    /**
     * @return array|CartItem[]
     */
    public function items() : array
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
}
