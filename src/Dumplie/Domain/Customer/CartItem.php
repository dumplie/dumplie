<?php

declare (strict_types = 1);

namespace Dumplie\Domain\Customer;

use Dumplie\Domain\Customer\Exception\InvalidArgumentException;
use Dumplie\Domain\SharedKernel\Money\Price;

final class CartItem
{
    /**
     * @var Product
     */
    private $product;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @param Product $product
     * @param int     $quantity
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Product $product, int $quantity)
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException();
        }

        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * @return Price
     */
    public function totalPrice() : Price
    {
        return $this->product->price()->multiply($this->quantity);
    }
}
