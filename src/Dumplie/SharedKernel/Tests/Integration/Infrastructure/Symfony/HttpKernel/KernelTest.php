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
    /**
     * @var KernelStub
     */
    private $kernel;

    public function setUp()
    {
        $this->kernel = new KernelStub();
    }

    public function test_if_container_is_instance_of_service_locator()
    {
        $this->kernel->boot();

        $this->assertInstanceOf(ServiceLocator::class, $this->kernel->getContainer());
    }

    public function test_exntesion_endpoints_execution()
    {
        $extensionProphecy = $this->prophesize(Extension::class);

        $extensionProphecy->configure(Argument::type(ServiceContainer::class))->shouldBeCalled();
        $extensionProphecy->boot(Argument::type(ServiceLocator::class))->shouldBeCalled();

        $this->kernel->setExtensions([$extensionProphecy->reveal()]);

        $this->kernel->boot();
    }
}