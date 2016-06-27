<?php

namespace Spec\Dumplie\Application\Command;

use Dumplie\Application\Command\Command;
use Dumplie\Application\Command\Extension;
use Dumplie\Application\ServiceLocator;
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
