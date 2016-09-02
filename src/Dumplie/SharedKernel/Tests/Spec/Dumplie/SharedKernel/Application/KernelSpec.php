<?php

namespace Spec\Dumplie\SharedKernel\Application;

use Dumplie\Inventory\Application\Extension\CoreExtension;
use Dumplie\SharedKernel\Application\Exception\KernelException;
use Dumplie\SharedKernel\Application\Extension;
use Dumplie\SharedKernel\Application\ServiceContainer;
use Dumplie\SharedKernel\Infrastructure\Symfony\DependencyInjeciton\ServiceLocator;
use PhpSpec\ObjectBehavior;

class KernelSpec extends ObjectBehavior
{
    function let(Extension $extension)
    {
        $extension->dependsOn()->willReturn([]);
    }

    function it_builds_service_container_using_extensions(Extension $extension, ServiceContainer $container)
    {
        $extension->build($container)->shouldBeCalled();

        $this->register($extension);

        $this->build($container);
    }

    function it_builds_itself_using_extensions(Extension $extension, ServiceLocator $locator, ServiceContainer $container)
    {
        $extension->build($container)->shouldBeCalled();
        $extension->boot($locator)->shouldBeCalled();

        $this->register($extension);

        $this->build($container);
        $this->boot($locator);
    }

    function it_throws_exception_when_booting_not_built_kernel(ServiceLocator $locator)
    {
        $this->shouldThrow(KernelException::class)->during('boot', [$locator]);
    }

    function it_throws_exception_when_extension_is_missing_before_build(Extension $extension, ServiceContainer $container)
    {
        $extension->dependsOn()->willReturn([CoreExtension::class]);

        $this->register($extension);

        $this->shouldThrow(KernelException::class)->during('build', [$container]);
    }
}
