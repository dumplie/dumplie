<?php

namespace Spec\Dumplie\CustomerService\Domain;

use Dumplie\CustomerService\Domain\Exception\InvalidTransitionException;
use Dumplie\CustomerService\Domain\Order;
use Dumplie\CustomerService\Domain\OrderId;
use Dumplie\CustomerService\Domain\PaymentId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(PaymentId::generate(), new Order(OrderId::generate(), new \DateTimeImmutable()));
    }

    function it_can_not_be_paid_when_already_paid() {
        $this->pay();
        $this->shouldThrow(InvalidTransitionException::class)->during('pay');
    }

    function it_can_not_be_rejected_when_paid() {
        $this->pay();
        $this->shouldThrow(InvalidTransitionException::class)->during('reject');
    }

    function it_can_not_be_rejected_when_already_rejected() {
        $this->reject();
        $this->shouldThrow(InvalidTransitionException::class)->during('reject');
    }

    function it_can_not_be_paid_when_rejected() {
        $this->reject();
        $this->shouldThrow(InvalidTransitionException::class)->during('pay');
    }
}
