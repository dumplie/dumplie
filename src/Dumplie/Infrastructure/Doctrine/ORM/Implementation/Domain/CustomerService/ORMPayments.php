<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\Doctrine\ORM\Implementation\Domain\CustomerService;

use Doctrine\ORM\EntityManager;
use Dumplie\Domain\CustomerService\Exception\PaymentNotFoundException;
use Dumplie\Domain\CustomerService\Payment;
use Dumplie\Domain\CustomerService\PaymentId;
use Dumplie\Domain\CustomerService\Payments;

final class ORMPayments implements Payments
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param PaymentId $paymentId
     * @return Payment
     * @throws PaymentNotFoundException
     */
    public function getById(PaymentId $paymentId) : Payment
    {
        $payment = $this->entityManager->getRepository(Payment::class)->findOneBy(['id.id' => $paymentId]);

        if (is_null($payment)) {
            throw new PaymentNotFoundException();
        }

        return $payment;
    }

    /**
     * @param Payment $payment
     */
    public function add(Payment $payment)
    {
        $this->entityManager->persist($payment);
    }
}