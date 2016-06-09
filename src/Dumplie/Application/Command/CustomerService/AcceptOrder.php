<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\CustomerService;

class AcceptOrder
{
    /**
     * @var string
     */
    private $orderId;

    /**
     * AcceptOrder constructor.
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
