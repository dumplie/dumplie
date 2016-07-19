<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Tests\Integration\Infrastructure\Symfony\DependencyInjection;

use Dumplie\SharedKernel\Application\ServiceContainer\Definition;
use Dumplie\SharedKernel\Infrastructure\Symfony\DependencyInjeciton\ServiceContainer;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition as SymfonyDefinition;

final class ServiceContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    /**
     * @var ServiceContainer
     */
    private $serviceContainer;

    public function setUp()
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->serviceContainer = new ServiceContainer($this->containerBuilder);
    }

    public function test_service_registration()
    {
        $this->serviceContainer->register('test', new Definition(\stdClass::class));

        $this->assertTrue($this->containerBuilder->has('test'));
        $this->assertSame(
            \stdClass::class,
            $this->containerBuilder->getDefinition('test')->getClass()
        );
    }

    public function test_accessing_service_by_alias()
    {
        $this->containerBuilder->register('service', new SymfonyDefinition(\stdClass::class));
        $this->containerBuilder->setAlias('service_alias', 'service');

        $this->assertTrue($this->serviceContainer->definitionExists('service_alias'));

        $this->assertSame(
            \stdClass::class,
            $this->serviceContainer->definitionClass('service_alias')
        );
    }
}