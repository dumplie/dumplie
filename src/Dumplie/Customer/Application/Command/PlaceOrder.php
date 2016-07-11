<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Command;

use Dumplie\SharedKernel\Application\Command\Command;
use Dumplie\SharedKernel\Application\Command\CommandSerialize;

final class PlaceOrder implements Command
{
    use CommandSerialize;
    /**
     * @var string
     */
    private $cartId;

    /**
     * @var string
     */
    private $orderId;

    /**
     * @param string $cartId
     * @param string $orderId
     */
    public function __construct(string $cartId, string $orderId)
    {
        $this->cartId = $cartId;
        $this->orderId = $orderId;
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
    public function orderId() : string
    {
        return $this->orderId;
    }
}