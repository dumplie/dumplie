<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Tests\Context\Tactician;

use Dumplie\SharedKernel\Application\Command\ExtensionRegistry;
use Dumplie\SharedKernel\Infrastructure\InMemory\InMemoryServiceLocator;
use Dumplie\SharedKernel\Infrastructure\Tactician\CommandBus;
use Dumplie\SharedKernel\Infrastructure\Tactician\Middleware\ExtensionMiddleware;
use Dumplie\SharedKernel\Tests\Context\CommandBusFactory;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\CommandBus as Tactician;

final class TacticianFactory implements CommandBusFactory
{
    /**
     * @param array $handlers
     * @param array $commandExtension
     * @return \Dumplie\SharedKernel\Application\CommandBus
     */
    public function create(array $handlers = [], array $commandExtension = []) : \Dumplie\SharedKernel\Application\CommandBus
    {
        $commandHandlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new InMemoryLocator($handlers),
            new HandleInflector()
        );

        $serviceLocator = new InMemoryServiceLocator();
        $extensionRegistry = new ExtensionRegistry($serviceLocator);

        foreach ($commandExtension as $extension) {
            $extensionRegistry->register($extension);
        }

        $extensionMiddleware = new ExtensionMiddleware($extensionRegistry);

        return new CommandBus(new Tactician([
            $extensionMiddleware,
            $commandHandlerMiddleware
        ]));
    }
}