<?php

namespace Spec\Dumplie\Domain\CustomerService;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OrderSpec extends ObjectBehavior
{
    function it_can_not_be_accepted_when_unpaid()
    {
        $this->shouldThrow(InvalidTransitionException::class)->during('accept');
    }

    function it_can_not_be_rejected_when_unpaid()
    {
        $this->shouldThrow(InvalidTransitionException::class)->during('reject');
    }

    function it_can_not_be_prepared_when_unpaid()
    {
        $this->shouldThrow(InvalidTransitionException::class)->during('prepare');
    }

    function it_can_not_be_refunded_when_unpaid()
    {
        $this->shouldThrow(InvalidTransitionException::class)->during('refund');
    }

    function it_can_not_be_sent_when_unpaid()
    {
        $this->shouldThrow(InvalidTransitionException::class)->during('send');
    }

    function it_can_not_be_paid_when_paid()
    {
        $this->pay();
        $this->shouldThrow(InvalidTransitionException::class)->during('pay');
    }

    function it_can_not_be_cancelled_when_paid()
    {
        $this->pay();
        $this->shouldThrow(InvalidTransitionException::class)->during('cancel');
    }

    function it_can_not_be_prepared_when_paid()
    {
        $this->pay();
        $this->shouldThrow(InvalidTransitionException::class)->during('prepare');
    }

    function it_can_not_be_refunded_when_paid()
    {
        $this->pay();
        $this->shouldThrow(InvalidTransitionException::class)->during('refund');
    }

    function it_can_not_be_sent_when_paid()
    {
        $this->pay();
        $this->shouldThrow(InvalidTransitionException::class)->during('send');
    }

    function it_can_not_be_paid_when_cancelled()
    {
        $this->cancel();
        $this->shouldThrow(InvalidTransitionException::class)->during('pay');
    }

    function it_can_not_be_cancelled_when_cancelled()
    {
        $this->cancel();
        $this->shouldThrow(InvalidTransitionException::class)->during('cancel');
    }

    function it_can_not_be_accepted_when_cancelled()
    {
        $this->cancel();
        $this->shouldThrow(InvalidTransitionException::class)->during('accept');
    }

    function it_can_not_be_rejected_when_cancelled()
    {
        $this->cancel();
        $this->shouldThrow(InvalidTransitionException::class)->during('reject');
    }

    function it_can_not_be_prepared_when_cancelled()
    {
        $this->cancel();
        $this->shouldThrow(InvalidTransitionException::class)->during('prepare');
    }

    function it_can_not_be_refunded_when_cancelled()
    {
        $this->cancel();
        $this->shouldThrow(InvalidTransitionException::class)->during('refund');
    }

    function it_can_not_be_sent_when_cancelled()
    {
        $this->cancel();
        $this->shouldThrow(InvalidTransitionException::class)->during('send');
    }

    function it_can_not_be_paid_when_accepted()
    {
        $this->pay();
        $this->accept();
        $this->shouldThrow(InvalidTransitionException::class)->during('pay');
    }

    function it_can_not_be_cancelled_when_accepted()
    {
        $this->pay();
        $this->accept();
        $this->shouldThrow(InvalidTransitionException::class)->during('cancel');
    }

    function it_can_not_be_accepted_when_accepted()
    {
        $this->pay();
        $this->accept();
        $this->shouldThrow(InvalidTransitionException::class)->during('accept');
    }

    function it_can_not_be_rejected_when_accepted()
    {
        $this->pay();
        $this->accept();
        $this->shouldThrow(InvalidTransitionException::class)->during('reject');
    }

    function it_can_not_be_sent_when_accepted()
    {
        $this->pay();
        $this->accept();
        $this->shouldThrow(InvalidTransitionException::class)->during('send');
    }

    function it_can_not_be_paid_when_rejected()
    {
        $this->pay();
        $this->reject();
        $this->shouldThrow(InvalidTransitionException::class)->during('pay');
    }

    function it_can_not_be_cancelled_when_rejected()
    {
        $this->pay();
        $this->reject();
        $this->shouldThrow(InvalidTransitionException::class)->during('cancel');
    }

    function it_can_not_be_accepted_when_rejected()
    {
        $this->pay();
        $this->reject();
        $this->shouldThrow(InvalidTransitionException::class)->during('accept');
    }

    function it_can_not_be_rejected_when_rejected()
    {
        $this->pay();
        $this->reject();
        $this->shouldThrow(InvalidTransitionException::class)->during('reject');
    }

    function it_can_not_be_prepared_when_rejected()
    {
        $this->pay();
        $this->reject();
        $this->shouldThrow(InvalidTransitionException::class)->during('prepare');
    }

    function it_can_not_be_refunded_when_rejected() {
        $this->pay();
        $this->reject();
        $this->shouldThrow(InvalidTransitionException::class)->during('refund');
    }

    function it_can_not_be_sent_when_rejected() {
        $this->pay();
        $this->reject();
        $this->shouldThrow(InvalidTransitionException::class)->during('send');
    }

    function it_can_not_be_paid_when_prepared() {
        $this->pay();
        $this->accept();
        $this->prepare();
        $this->shouldThrow(InvalidTransitionException::class)->during('pay');
    }

    function it_can_not_be_cancelled_when_prepared() {
        $this->pay();
        $this->accept();
        $this->prepare();
        $this->shouldThrow(InvalidTransitionException::class)->during('cancel');
    }

    function it_can_not_be_accepted_when_prepared() {
        $this->pay();
        $this->accept();
        $this->prepare();
        $this->shouldThrow(InvalidTransitionException::class)->during('accept');
    }

    function it_can_not_be_rejected_when_prepared() {
        $this->pay();
        $this->accept();
        $this->prepare();
        $this->shouldThrow(InvalidTransitionException::class)->during('reject');
    }

    function it_can_not_be_prepared_when_prepared() {
        $this->pay();
        $this->accept();
        $this->prepare();
        $this->shouldThrow(InvalidTransitionException::class)->during('prepare');
    }

    function it_can_not_be_refunded_when_prepared() {
        $this->pay();
        $this->accept();
        $this->prepare();
        $this->shouldThrow(InvalidTransitionException::class)->during('refund');
    }

    function it_can_not_be_paid_when_refunded() {
        $this->pay();
        $this->accept();
        $this->refund();
        $this->shouldThrow(InvalidTransitionException::class)->during('pay');
    }

    function it_can_not_be_cancelled_when_refunded() {
        $this->pay();
        $this->accept();
        $this->refund();
        $this->shouldThrow(InvalidTransitionException::class)->during('cancel');
    }

    function it_can_not_be_accepted_when_refunded() {
        $this->pay();
        $this->accept();
        $this->refund();
        $this->shouldThrow(InvalidTransitionException::class)->during('accept');
    }

    function it_can_not_be_rejected_when_refunded() {
        $this->pay();
        $this->accept();
        $this->refund();
        $this->shouldThrow(InvalidTransitionException::class)->during('reject');
    }

    function it_can_not_be_prepared_when_refunded() {
        $this->pay();
        $this->accept();
        $this->refund();
        $this->shouldThrow(InvalidTransitionException::class)->during('prepare');
    }

    function it_can_not_be_refunded_when_refunded() {
        $this->pay();
        $this->accept();
        $this->refund();
        $this->shouldThrow(InvalidTransitionException::class)->during('refund');
    }

    function it_can_not_be_sent_when_refunded() {
        $this->pay();
        $this->accept();
        $this->refund();
        $this->shouldThrow(InvalidTransitionException::class)->during('send');
    }

    function it_can_not_be_paid_when_sent() {
        $this->pay();
        $this->accept();
        $this->prepare();
        $this->send();
        $this->shouldThrow(InvalidTransitionException::class)->during('pay');
    }

    function it_can_not_be_cancelled_when_sent() {
        $this->pay();
        $this->accept();
        $this->prepare();
        $this->send();
        $this->shouldThrow(InvalidTransitionException::class)->during('cancel');
    }

    function it_can_not_be_accepted_when_sent() {
        $this->pay();
        $this->accept();
        $this->prepare();
        $this->send();
        $this->shouldThrow(InvalidTransitionException::class)->during('accept');
    }

    function it_can_not_be_rejected_when_sent() {
        $this->pay();
        $this->accept();
        $this->prepare();
        $this->send();
        $this->shouldThrow(InvalidTransitionException::class)->during('reject');
    }

    function it_can_not_be_prepared_when_sent() {
        $this->pay();
        $this->accept();
        $this->prepare();
        $this->send();
        $this->shouldThrow(InvalidTransitionException::class)->during('prepare');
    }

    function it_can_not_be_refunded_when_sent() {
        $this->pay();
        $this->accept();
        $this->prepare();
        $this->send();
        $this->shouldThrow(InvalidTransitionException::class)->during('refund');
    }

    function it_can_not_be_sent_when_sent() {
        $this->pay();
        $this->accept();
        $this->prepare();
        $this->send();
        $this->shouldThrow(InvalidTransitionException::class)->during('send');
    }
}
