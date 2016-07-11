<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Domain;

use Dumplie\CustomerService\Domain\Exception\InvalidTransitionException;
use Dumplie\CustomerService\Domain\OrderState\Created;

final class Order
{
    /**
     * @var OrderId
     */
    private $id;

    /**
     * @var OrderState
     */
    private $state;

    /**
     * @var null|\DateTimeInterface
     */
    private $wasCreated;

    /**
     * @var null|\DateTimeInterface
     */
    private $wasAcceptedAt;

    /**
     * @var null|\DateTimeInterface
     */
    private $wasRejectedAt;

    /**
     * @var null|\DateTimeInterface
     */
    private $wasPreparedAt;

    /**
     * @var null|\DateTimeInterface
     */
    private $wasRefundedAt;

    /**
     * @var null|\DateTimeInterface
     */
    private $wasSentAt;

    /**
     * @param OrderId $id
     * @param \DateTimeImmutable $placedAt
     */
    public function __construct(OrderId $id, \DateTimeImmutable $placedAt)
    {
        $this->id = $id;
        $this->wasCreated = $placedAt;
        $this->state = new Created();
    }

    /**
     * @return OrderId
     */
    public function id() : OrderId
    {
        return $this->id;
    }

    /**
     * @throws InvalidTransitionException
     */
    public function accept()
    {
        $this->state = $this->state->accept();
        $this->wasAcceptedAt = new \DateTimeImmutable();
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
     * @throws InvalidTransitionException
     */
    public function prepare()
    {
        $this->state = $this->state->prepare();
        $this->wasPreparedAt = new \DateTimeImmutable();
    }

    /**
     * @throws InvalidTransitionException
     */
    public function refund()
    {
        $this->state = $this->state->refund();
        $this->wasRefundedAt = new \DateTimeImmutable();
    }

    /**
     * @throws InvalidTransitionException
     */
    public function send()
    {
        $this->state = $this->state->send();
        $this->wasSentAt = new \DateTimeImmutable();
    }
}
