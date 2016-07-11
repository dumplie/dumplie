<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Command;

use Dumplie\Customer\Application\Command\AddToCart;
use Dumplie\Customer\Domain\CartId;
use Dumplie\Customer\Domain\Carts;
use Dumplie\Customer\Domain\Products;
use Dumplie\SharedKernel\Domain\Product\SKU;

final class AddToCartHandler
{
    /**
     * @var Carts
     */
    private $carts;

    /**
     * @var Products
     */
    private $products;

    /**
     * AddToCartHandler constructor.
     *
     * @param Products $products
     * @param Carts    $carts
     */
    public function __construct(Products $products, Carts $carts)
    {
        $this->products = $products;
        $this->carts = $carts;
    }

    /**
     * @param AddToCart $command
     *
     * @throws \Exception
     */
    public function handle(AddToCart $command)
    {
        $product = $this->products->getBySku(new SKU($command->sku()));

        $cart = $this->carts->getById(new CartId($command->cartId()));
        $cart->add($product, $command->quantity());
    }
}
