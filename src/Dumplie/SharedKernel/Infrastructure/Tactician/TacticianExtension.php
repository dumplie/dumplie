<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\Tactician;

use Dumplie\SharedKernel\Application\Extension;
use Dumplie\SharedKernel\Application\ServiceContainer;
use Dumplie\SharedKernel\Application\ServiceLocator;
use Dumplie\SharedKernel\Application\Services;
use Dumplie\SharedKernel\Infrastructure\Tactician\Middleware\ExtensionMiddleware;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\CommandBus as Tactician;

final class TacticianExtension implements Extension
{
    const TACTICAIN = 'dumplie.tactician.command_bus';
    const TACTICAIN_COMMAND_EXTRACTOR= 'dumplie.tactician.command.extractor';
    const TACTICAIN_HANDLE_INFLECTOR= 'dumplie.tactician.handle_inflector';
    const TACTICAIN_HANDLER_LOCATOR = 'dumplie.tactician.handler.locator';
    const TACTICAIN_MIDDLEWARE_HANDLER = 'dumplie.tactician.middleware.handler';
    const TACTICAIN_MIDDLEWARE_EXTENSION = 'dumplie.tactician.middleware.extension';

    /**
     * @return array
     */
    public function dependsOn() : array
    {
        return [
            Extension\CoreExtension::class
        ];
    }

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function build(ServiceContainer $serviceContainer)
    {
        $serviceContainer->register(
            self::TACTICAIN_HANDLER_LOCATOR,
            new ServiceContainer\Definition(
                MapLocator::class,
                [
                    new ServiceContainer\ArgumentService(Services::KERNEL_COMMAND_HANDLER_MAP)
                ]
            )
        );
        $serviceContainer->register(
            self::TACTICAIN_COMMAND_EXTRACTOR,
            new ServiceContainer\Definition(
                ClassNameExtractor::class
            )
        );
        $serviceContainer->register(
            self::TACTICAIN_HANDLE_INFLECTOR,
            new ServiceContainer\Definition(
                HandleInflector::class
            )
        );
        $serviceContainer->register(
            self::TACTICAIN_MIDDLEWARE_HANDLER,
            new ServiceContainer\Definition(
                CommandHandlerMiddleware::class,
                [
                    new ServiceContainer\ArgumentService(self::TACTICAIN_COMMAND_EXTRACTOR),
                    new ServiceContainer\ArgumentService(self::TACTICAIN_HANDLER_LOCATOR),
                    new ServiceContainer\ArgumentService(self::TACTICAIN_HANDLE_INFLECTOR),
                ]
            )
        );
        $serviceContainer->register(
            self::TACTICAIN_MIDDLEWARE_EXTENSION,
            new ServiceContainer\Definition(
                ExtensionMiddleware::class,
                [
                    new ServiceContainer\ArgumentService(Services::KERNEL_COMMAND_EXTENSION_REGISTRY)
                ]
            )
        );
        $serviceContainer->register(
            self::TACTICAIN,
            new ServiceContainer\Definition(
                Tactician::class,
                [
                    new ServiceContainer\ArgumentCollection([
                        new ServiceContainer\ArgumentService(self::TACTICAIN_MIDDLEWARE_EXTENSION),
                        new ServiceContainer\ArgumentService(self::TACTICAIN_MIDDLEWARE_HANDLER)
                    ])
                ]
            )
        );
        $serviceContainer->register(
            Services::KERNEL_COMMAND_BUS,
            new ServiceContainer\Definition(
                CommandBus::class,
                [
                    new ServiceContainer\ArgumentService(self::TACTICAIN)
                ]
            )
        );
    }

    public function boot(ServiceLocator $serviceLocator)
    {
    }
}