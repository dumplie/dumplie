<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Application\Command;

use Dumplie\SharedKernel\Application\Command\Command;
use Dumplie\SharedKernel\Application\Command\CommandSerialize;

final class CreatePayment implements Command
{
    use CommandSerialize;

    /**
     * @var string
     */
    private $orderId;

    /**
     * @var string
     */
    private $paymentId;

    /**
     * @param string $orderId
     * @param string $paymentId
     */
    public function __construct(string $orderId, string $paymentId)
    {
        $this->orderId = $orderId;
        $this->paymentId = $paymentId;
    }

    /**
     * @return string
     */
    public function orderId() : string
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function paymentId() : string
    {
        return $this->paymentId;
    }
}
