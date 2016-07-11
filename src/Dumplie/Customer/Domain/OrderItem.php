<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Domain;

use Dumplie\SharedKernel\Domain\Money\Price;
use Dumplie\SharedKernel\Domain\Product\SKU;

final class OrderItem
{
    /**
     * @var SKU
     */
    private $sku;

    /**
     * @var Price
     */
    private $price;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @param SKU $sku
     * @param Price $price
     * @param int $quantity
     */
    public function __construct(SKU $sku, Price $price, int $quantity)
    {
        $this->sku = $sku;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    /**
     * @param Product $product
     * @param int $quantity
     * @return OrderItem
     */
    public static function createFromProduct(Product $product, int $quantity) : OrderItem
    {
        return new self($product->sku(), $product->price(), $quantity);
    }

    /**
     * @return SKU
     */
    public function sku() : SKU
    {
        return $this->sku;
    }

    /**
     * @return Price
     */
    public function price() : Price
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function quantity() : int
    {
        return $this->quantity;
    }
}