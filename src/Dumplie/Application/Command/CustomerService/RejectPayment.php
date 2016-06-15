<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\CustomerService;

final class RejectPayment
{
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
