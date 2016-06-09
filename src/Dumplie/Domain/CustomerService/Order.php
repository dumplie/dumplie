<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;
use Dumplie\Domain\CustomerService\OrderState\Unpaid;

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
    private $wasPaidAt;

    /**
     * @var null|\DateTimeInterface
     */
    private $wasCanceledAt;

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
     * Order constructor.
     */
    public function __construct()
    {
        $this->id = OrderId::generate();
        $this->state = new Unpaid();
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
    public function pay()
    {
        $this->state = $this->state->pay();
        $this->wasPaidAt = new \DateTimeImmutable();
    }

    /**
     * @throws InvalidTransitionException
     */
    public function cancel()
    {
        $this->state = $this->state->cancel();
        $this->wasCanceledAt = new \DateTimeImmutable();
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
