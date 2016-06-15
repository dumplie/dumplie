<?php

namespace Spec\Dumplie\Domain\CustomerService;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;
use Dumplie\Domain\CustomerService\Order;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new Order());
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
