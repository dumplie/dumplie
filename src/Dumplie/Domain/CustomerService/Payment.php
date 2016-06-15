<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;
use Dumplie\Domain\CustomerService\PaymentState\Unpaid;

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
    private $wasPaidAt;

    /**
     * @var null|\DateTimeInterface
     */
    private $wasRejectedAt;

    /**
     * Order constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->id = PaymentId::generate();
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
}
