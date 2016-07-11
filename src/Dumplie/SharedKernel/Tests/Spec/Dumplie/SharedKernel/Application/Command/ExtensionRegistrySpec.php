<?php

namespace Spec\Dumplie\SharedKernel\Application\Command;

use Dumplie\SharedKernel\Application\Command\Command;
use Dumplie\SharedKernel\Application\Command\Extension;
use Dumplie\SharedKernel\Application\ServiceLocator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExtensionRegistrySpec extends ObjectBehavior
{
    function let(ServiceLocator $serviceLocator)
    {
        $this->beConstructedWith($serviceLocator);
    }

    function it_executes_pre_endpoint_for_matching_commands(Extension $extension, Command $command, ServiceLocator $serviceLocator)
    {
        $extension->expands($command)->willReturn(true);
        $extension->pre($command, $serviceLocator)->shouldBeCalled();

        $this->register($extension);

        $this->pre($command, $serviceLocator);
    }

    function it_executes_post_endpoint_for_matching_commands(Extension $extension, Command $command, ServiceLocator $serviceLocator)
    {
        $extension->expands($command)->willReturn(true);
        $extension->post($command, $serviceLocator)->shouldBeCalled();

        $this->register($extension);

        $this->post($command, $serviceLocator);
    }
}
