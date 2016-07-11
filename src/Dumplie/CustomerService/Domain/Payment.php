<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Domain;

use Dumplie\CustomerService\Domain\Exception\InvalidTransitionException;
use Dumplie\CustomerService\Domain\PaymentState\Paid;
use Dumplie\CustomerService\Domain\PaymentState\Rejected;
use Dumplie\CustomerService\Domain\PaymentState\Unpaid;

final class Payment
{
    /**
     * @var PaymentId
     */
    private $id;

    /**
     * @var OrderId
     */
    private $orderId;

    /**
     * @var PaymentState
     */
    private $state;

    /**
     * @var null|\DateTimeInterface
     */
    private $createdAt;
    
    /**
     * @var null|\DateTimeInterface
     */
    private $wasPaidAt;

    /**
     * @var null|\DateTimeInterface
     */
    private $wasRejectedAt;

    /**
     * @param PaymentId $id
     * @param Order $order
     */
    public function __construct(PaymentId $id, Order $order)
    {
        $this->id = $id;
        $this->createdAt = new \DateTimeImmutable();
        $this->orderId = $order->id();
        $this->state = new Unpaid();
    }

    /**
     * @return PaymentId
     */
    public function id() : PaymentId
    {
        return $this->id;
    }

    /**
     * @throws InvalidTransitionException
     */
    public function pay()
    {
        $this->state = $this->state->pay();
        $this->wasPaidAt = new \DateTimeImmutable();
    }

    /**
     * @throws InvalidTransitionException
     */
    public function reject()
    {
        $this->state = $this->state->reject();
        $this->wasRejectedAt = new \DateTimeImmutable();
    }

    /**
     * @return bool
     */
    public function isRejected() : bool
    {
        return $this->state instanceOf Rejected;
    }

    /**
     * @return bool
     */
    public function isPaid() : bool
    {
        return $this->state instanceOf Paid;
    }
}
