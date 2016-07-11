<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Tests\Integration\Application\InMemory;

use Dumplie\SharedKernel\Application\Command\Command;
use Dumplie\SharedKernel\Application\Command\Extension;
use Dumplie\SharedKernel\Application\Command\ExtensionRegistry;
use Dumplie\SharedKernel\Application\ServiceLocator;
use Dumplie\Customer\Domain\CartId;
use Dumplie\SharedKernel\Infrastructure\InMemory\InMemoryServiceLocator;
use Dumplie\SharedKernel\Infrastructure\Tactician\Middleware\ExtensionMiddleware;
use League\Tactician\CommandBus as Tactician;
use Dumplie\Customer\Application\Command\CreateCart;
use Dumplie\Customer\Application\Command\CreateCartHandler;
use Dumplie\Customer\Infrastructure\InMemory\InMemoryCarts;
use Dumplie\SharedKernel\Infrastructure\InMemory\Transaction\Factory;
use Dumplie\SharedKernel\Infrastructure\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use Prophecy\Argument;

class CommandExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceLocator
     */
    private $serviceLocator;

    /**
     * @var ExtensionRegistry
     */
    private $extensionRegistry;

    /**
     * @var CommandBus
     */
    private $commandBus;

    public function setUp()
    {
        $factory = new Factory();
        $carts = new InMemoryCarts();

        $commandHandlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new InMemoryLocator([
                CreateCart::class => new CreateCartHandler($carts, $factory),
            ]),
            new HandleInflector()
        );

        $this->serviceLocator = new InMemoryServiceLocator();
        $this->extensionRegistry = new ExtensionRegistry($this->serviceLocator);
        $extensionMiddleware = new ExtensionMiddleware($this->extensionRegistry);

        $this->commandBus = new CommandBus(new Tactician([
            $extensionMiddleware,
            $commandHandlerMiddleware
        ]));
    }

    public function test_command_extension_points()
    {
        $command = new CreateCart((string)CartId::generate(), 'PLN');

        $extension = $this->prophesize(Extension::class);
        $extension->expands($command)->willReturn(true);
        $extension->pre($command, $this->serviceLocator)
            ->shouldBeCalled();
        $extension->post($command, $this->serviceLocator)
            ->shouldBeCalled();

        $this->extensionRegistry->register($extension->reveal());

        $this->commandBus->handle($command);
    }

    public function test_extension_execution_without_specified_order()
    {
        $command = new CreateCart((string) CartId::generate(), 'PLN');

        $executionOrder = [];
        $prePromise = function() use (&$executionOrder) {
            $executionOrder[] = spl_object_hash($this);
        };
        $extension1 = $this->createExtensionProphecy($command, $prePromise);
        $extension2 = $this->createExtensionProphecy($command, $prePromise);

        $this->extensionRegistry->register($extension1->reveal());
        $this->extensionRegistry->register($extension2->reveal());

        $this->extensionRegistry->pre($command);

        $this->assertEquals(
            [spl_object_hash($extension1), spl_object_hash($extension2)],
            $executionOrder
        );
    }

    public function test_extensions_execution_order()
    {
        $command = new CreateCart((string) CartId::generate(), 'PLN');

        $executionOrder = [];
        $prePromise = function() use (&$executionOrder) {
            $executionOrder[] = spl_object_hash($this);
        };
        $extension1 = $this->createExtensionProphecy($command, $prePromise);
        $extension2 = $this->createExtensionProphecy($command, $prePromise);

        $this->extensionRegistry->register($extension1->reveal(), 0);
        $this->extensionRegistry->register($extension2->reveal(), 1);

        $this->extensionRegistry->pre($command);

        $this->assertEquals(
            [spl_object_hash($extension2), spl_object_hash($extension1)],
            $executionOrder
        );
    }

    /**
     * @param Command $command
     * @param \Closure|null $prePromise
     * @param \Closure|null $postPromise
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    private function createExtensionProphecy(Command $command, \Closure $prePromise = null, \Closure $postPromise = null)
    {
        $extension = $this->prophesize(Extension::class);
        $extension->expands($command)->willReturn(true);
        if ($prePromise !== null) {
            $extension->pre($command, $this->serviceLocator)->will($prePromise);
        }
        if ($postPromise !== null) {
            $extension->post($command, $this->serviceLocator)->will($postPromise);
        }

        return $extension;
    }
}