<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Infrastructure\Doctrine\ORM\Domain;

use Doctrine\ORM\EntityManager;
use Dumplie\CustomerService\Domain\Exception\PaymentNotFoundException;
use Dumplie\CustomerService\Domain\Payment;
use Dumplie\CustomerService\Domain\PaymentId;
use Dumplie\CustomerService\Domain\Payments;

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