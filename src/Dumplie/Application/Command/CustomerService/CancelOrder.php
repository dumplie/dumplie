<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\CustomerService;

use Dumplie\Application\Command\Command;
use Dumplie\Application\Command\CommandSerialize;

class CancelOrder implements Command
{
    use CommandSerialize;
    
    /**
     * @var string
     */
    private $orderId;

    /**
     * CancelOrder constructor.
     *
     * @param string $orderId
     */
    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * @return string
     */
    public function orderId() : string
    {
        return $this->orderId;
    }
}
