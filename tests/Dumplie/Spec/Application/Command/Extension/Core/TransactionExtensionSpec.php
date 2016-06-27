<?php

namespace Spec\Dumplie\Application\Command\Extension\Core;

use Dumplie\Application\Command\Customer\CreateCart;
use Dumplie\Application\Command\Extension;
use Dumplie\Application\ServiceLocator;
use Dumplie\Application\Transaction\Factory;
use Dumplie\Application\Transaction\Transaction;
use Dumplie\Domain\Customer\CartId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TransactionExtensionSpec extends ObjectBehavior
{
    function let(Factory $factory)
    {
        $this->beConstructedWith($factory);
    }

    function it_is_command_extension()
    {
        $this->shouldImplement(Extension::class);
    }

    function it_opens_transaction_in_pre_command_endpoint(Factory $factory, ServiceLocator $serviceLocator)
    {
        $factory->open()->shouldBeCalled();

        $this->pre(new CreateCart((string) CartId::generate(), "PLN"), $serviceLocator);
    }

    function it_commits_transaction_in_post_command_endpoint(Factory $factory, Transaction $transaction, ServiceLocator $serviceLocator)
    {
        $command = new CreateCart((string)CartId::generate(), "PLN");

        $factory->open()->willReturn($transaction);

        $transaction->commit()->shouldBeCalled();

        $this->pre($command, $serviceLocator);
        $this->post($command, $serviceLocator);
    }

    function it_rethrows_exception_and_rollbacks_transaction_in_catch_exception_command_endpoint(Factory $factory, Transaction $transaction, ServiceLocator $serviceLocator)
    {
        $command = new CreateCart((string)CartId::generate(), "PLN");

        $factory->open()->willReturn($transaction);

        $transaction->rollback()->shouldBeCalled();

        $this->pre($command, $serviceLocator);
        $this->shouldThrow(\Exception::class)->during(
            'catchException',
            [$command, new \Exception(), $serviceLocator]
        );
    }
}
