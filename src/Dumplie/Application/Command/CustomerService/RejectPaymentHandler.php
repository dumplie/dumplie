<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\CustomerService;

use Dumplie\Application\Transaction\Factory;
use Dumplie\Domain\CustomerService\PaymentId;
use Dumplie\Domain\CustomerService\Payments;

final class RejectPaymentHandler
{
    /**
     * @var Payments
     */
    private $payments;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * PayPaymentHandler constructor.
     *
     * @param Payments $payments
     * @param Factory  $factory
     */
    public function __construct(Payments $payments, Factory $factory)
    {
        $this->payments = $payments;
        $this->factory = $factory;
    }

    /**
     * @param RejectPayment $command
     *
     * @throws \Exception
     */
    public function handle(RejectPayment $command)
    {
        $transaction = $this->factory->open();

        try {
            $payment = $this->payments->getById(new PaymentId($command->paymentId()));
            $payment->reject();
            
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}
