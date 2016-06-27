<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\CustomerService;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;
use Dumplie\Domain\CustomerService\PaymentId;
use Dumplie\Domain\CustomerService\Payments;

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
