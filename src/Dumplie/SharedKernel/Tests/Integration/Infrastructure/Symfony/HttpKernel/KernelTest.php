<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Tests\Integration\Infrastructure\Symfony\HttpKernel;

use Dumplie\SharedKernel\Application\Extension;
use Dumplie\SharedKernel\Application\ServiceLocator;
use Dumplie\SharedKernel\Infrastructure\Symfony\DependencyInjeciton\ServiceContainer;
use Dumplie\SharedKernel\Tests\Double\Infrastructure\Symfony\HttpKernel\KernelStub;
use Prophecy\Argument;

final class KernelTest extends \PHPUnit_Framework_TestCase
{
    public function test_if_container_is_instance_of_service_locator()
    {
        $kernel = new KernelStub();

        $kernel->boot();

        $this->assertInstanceOf(ServiceLocator::class, $kernel->getContainer());
    }

    public function test_exntesion_endpoints_execution()
    {
        $extensionProphecy = $this->prophesize(Extension::class);

        $extensionProphecy->dependsOn()->willReturn([]);
        $extensionProphecy->build(Argument::type(ServiceContainer::class))->shouldBeCalled();
        $extensionProphecy->boot(Argument::type(ServiceLocator::class))->shouldBeCalled();

        $kernel = new KernelStub([$extensionProphecy->reveal()]);

        $kernel->setExtensions();

        $kernel->boot();
    }
}