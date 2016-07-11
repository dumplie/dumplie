<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Application\Command;

use Dumplie\CustomerService\Domain\Exception\InvalidTransitionException;
use Dumplie\CustomerService\Domain\PaymentId;
use Dumplie\CustomerService\Domain\Payments;

final class PayPaymentHandler
{
    /**
     * @var Payments
     */
    private $payments;

    /**
     * @param Payments $payments
     */
    public function __construct(Payments $payments)
    {
        $this->payments = $payments;
    }

    /**
     * @param PayPayment $command
     * @throws InvalidTransitionException
     */
    public function handle(PayPayment $command)
    {
        $payment = $this->payments->getById(new PaymentId($command->paymentId()));
        $payment->pay();
    }
}
