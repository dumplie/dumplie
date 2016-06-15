<?php

namespace Spec\Dumplie\Domain\CustomerService;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OrderSpec extends ObjectBehavior
{
    function it_can_not_be_prepared_when_created()
    {
        $this->shouldThrow(InvalidTransitionException::class)->during('prepare');
    }

    function it_can_not_be_refunded_when_created()
    {
        $this->shouldThrow(InvalidTransitionException::class)->during('refund');
    }

    function it_can_not_be_sent_when_created()
    {
        $this->shouldThrow(InvalidTransitionException::class)->during('send');
    }

    function it_can_not_be_accepted_when_accepted()
    {
        $this->accept();
        $this->shouldThrow(InvalidTransitionException::class)->during('accept');
    }

    function it_can_not_be_rejected_when_accepted()
    {
        $this->accept();
        $this->shouldThrow(InvalidTransitionException::class)->during('reject');
    }

    function it_can_not_be_sent_when_accepted()
    {
        $this->accept();
        $this->shouldThrow(InvalidTransitionException::class)->during('send');
    }

    function it_can_not_be_accepted_when_rejected()
    {
        $this->reject();
        $this->shouldThrow(InvalidTransitionException::class)->during('accept');
    }

    function it_can_not_be_rejected_when_rejected()
    {
        $this->reject();
        $this->shouldThrow(InvalidTransitionException::class)->during('reject');
    }

    function it_can_not_be_prepared_when_rejected()
    {
        $this->reject();
        $this->shouldThrow(InvalidTransitionException::class)->during('prepare');
    }

    function it_can_not_be_refunded_when_rejected()
    {
        $this->reject();
        $this->shouldThrow(InvalidTransitionException::class)->during('refund');
    }

    function it_can_not_be_sent_when_rejected()
    {
        $this->reject();
        $this->shouldThrow(InvalidTransitionException::class)->during('send');
    }

    function it_can_not_be_accepted_when_prepared()
    {
        $this->accept();
        $this->prepare();
        $this->shouldThrow(InvalidTransitionException::class)->during('accept');
    }

    function it_can_not_be_rejected_when_prepared()
    {
        $this->accept();
        $this->prepare();
        $this->shouldThrow(InvalidTransitionException::class)->during('reject');
    }

    function it_can_not_be_prepared_when_prepared()
    {
        $this->accept();
        $this->prepare();
        $this->shouldThrow(InvalidTransitionException::class)->during('prepare');
    }

    function it_can_not_be_refunded_when_prepared()
    {
        $this->accept();
        $this->prepare();
        $this->shouldThrow(InvalidTransitionException::class)->during('refund');
    }

    function it_can_not_be_accepted_when_refunded()
    {
        $this->accept();
        $this->refund();
        $this->shouldThrow(InvalidTransitionException::class)->during('accept');
    }

    function it_can_not_be_rejected_when_refunded()
    {
        $this->accept();
        $this->refund();
        $this->shouldThrow(InvalidTransitionException::class)->during('reject');
    }

    function it_can_not_be_prepared_when_refunded()
    {
        $this->accept();
        $this->refund();
        $this->shouldThrow(InvalidTransitionException::class)->during('prepare');
    }

    function it_can_not_be_refunded_when_refunded()
    {
        $this->accept();
        $this->refund();
        $this->shouldThrow(InvalidTransitionException::class)->during('refund');
    }

    function it_can_not_be_sent_when_refunded()
    {
        $this->accept();
        $this->refund();
        $this->shouldThrow(InvalidTransitionException::class)->during('send');
    }

    function it_can_not_be_accepted_when_sent()
    {
        $this->accept();
        $this->prepare();
        $this->send();
        $this->shouldThrow(InvalidTransitionException::class)->during('accept');
    }

    function it_can_not_be_rejected_when_sent()
    {
        $this->accept();
        $this->prepare();
        $this->send();
        $this->shouldThrow(InvalidTransitionException::class)->during('reject');
    }

    function it_can_not_be_prepared_when_sent()
    {
        $this->accept();
        $this->prepare();
        $this->send();
        $this->shouldThrow(InvalidTransitionException::class)->during('prepare');
    }

    function it_can_not_be_refunded_when_sent()
    {
        $this->accept();
        $this->prepare();
        $this->send();
        $this->shouldThrow(InvalidTransitionException::class)->during('refund');
    }

    function it_can_not_be_sent_when_sent()
    {
        $this->accept();
        $this->prepare();
        $this->send();
        $this->shouldThrow(InvalidTransitionException::class)->during('send');
    }
}
