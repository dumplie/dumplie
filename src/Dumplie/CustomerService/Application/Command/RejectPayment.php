<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Application\Command;

use Dumplie\SharedKernel\Application\Command\Command;
use Dumplie\SharedKernel\Application\Command\CommandSerialize;

final class RejectPayment implements Command
{
    use CommandSerialize;
    
    /**
     * @var string
     */
    private $paymentId;

    /**
     * PayPayment constructor.
     *
     * @param string $paymentId
     */
    public function __construct(string $paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * @return string
     */
    public function paymentId() : string
    {
        return $this->paymentId;
    }
}
