<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Command;

use Dumplie\SharedKernel\Application\Command\Command;
use Dumplie\SharedKernel\Application\Command\CommandSerialize;

final class AddToCart implements Command
{
    use CommandSerialize;
    
    /**
     * @var string
     */
    private $sku;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var string
     */
    private $cartId;

    /**
     * @param string $sku
     * @param int    $quantity
     * @param string $cartId
     */
    public function __construct(string $sku, int $quantity, string $cartId)
    {
        $this->sku = $sku;
        $this->quantity = $quantity;
        $this->cartId = $cartId;
    }

    /**
     * @return string
     */
    public function sku() : string
    {
        return $this->sku;
    }

    /**
     * @return int
     */
    public function quantity() : int
    {
        return $this->quantity;
    }

    /**
     * @return string
     */
    public function cartId() : string
    {
        return $this->cartId;
    }
}
