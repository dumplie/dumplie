<?php

namespace Spec\Dumplie\Infrastructure\Tactician\Middleware;

use Dumplie\Application\Command\Customer\AddToCart;
use Dumplie\Application\Command\Extension;
use Dumplie\Application\Command\ExtensionRegistry;
use Dumplie\Application\ServiceLocator;
use Dumplie\Domain\Customer\CartId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExtensionMiddlewareSpec extends ObjectBehavior
{
    /**
     * @var ExtensionRegistry
     */
    private $registry;

    function let(ServiceLocator $serviceLocator)
    {
        $this->registry = new ExtensionRegistry($serviceLocator->getWrappedObject());
        $this->beConstructedWith($this->registry);
    }

    function it_execute_extension_points(Extension $extension)
    {
        $command = new AddToCart("SKU", 1, (string) CartId::generate());

        $extension->pre($command, Argument::type(ServiceLocator::class))->shouldBeCalled();
        $extension->post($command, Argument::type(ServiceLocator::class))->shouldBeCalled();
        $extension->expands($command)->willReturn(true);

        $this->registry->register($extension->getWrappedObject());

        $this->execute($command, function() {});
    }
}
