<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Customer;

final class RemoveFromCart
{
    /**
     * @var string
     */
    private $cartId;

    /**
     * @var string
     */
    private $sku;

    /**
     * @param string $cartId
     * @param string $sku
     */
    public function __construct(string $cartId, string $sku)
    {
        $this->cartId = $cartId;
        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function cartId() : string
    {
        return $this->cartId;
    }

    /**
     * @return string
     */
    public function sku() : string
    {
        return $this->sku;
    }
}
