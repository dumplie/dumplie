<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\Extension;

use Dumplie\SharedKernel\Application\Command\Extension\Core\TransactionExtension;
use Dumplie\SharedKernel\Application\Command\ExtensionRegistry;
use Dumplie\SharedKernel\Application\CommandBus\CommandHandlerMap;
use Dumplie\SharedKernel\Application\Extension;
use Dumplie\SharedKernel\Application\ServiceContainer;
use Dumplie\SharedKernel\Application\ServiceLocator;
use Dumplie\SharedKernel\Application\Services;
use Dumplie\SharedKernel\Infrastructure\InMemory\InMemoryHandlerMap;
use Dumplie\SharedKernel\Infrastructure\Tactician\MapLocator;

final class CoreExtension implements Extension
{
    /**
     * @param ServiceContainer $serviceContainer
     */
    public function configure(ServiceContainer $serviceContainer)
    {
        if (!$serviceContainer->definitionExists(Services::KERNEL_SERVICE_LOCATOR)) {
            throw new \RuntimeException(sprintf('Service with id "%s" is missing in service container.', Services::KERNEL_SERVICE_LOCATOR));
        }

        $serviceContainer->register(Services::KERNEL_COMMAND_EXTENSION_REGISTRY, new ServiceContainer\Definition(
            ExtensionRegistry::class,
            [new ServiceContainer\ArgumentService(Services::KERNEL_SERVICE_LOCATOR)]
        ));

        $serviceContainer->register(
            Services::KERNEL_COMMAND_HANDLER_MAP,
            new ServiceContainer\Definition(
                InMemoryHandlerMap::class
            )
        );
    }

    /**
     * @param ServiceLocator $serviceLocator
     */
    public function boot(ServiceLocator $serviceLocator)
    {
        if (!$serviceLocator->has(Services::KERNEL_TRANSACTION_FACTORY)) {
            throw new \RuntimeException(sprintf('Service with id "%s" is missing in service locator.', Services::KERNEL_TRANSACTION_FACTORY));
        }

        $serviceLocator->get(Services::KERNEL_COMMAND_EXTENSION_REGISTRY)
            ->register(new TransactionExtension($serviceLocator->get(Services::KERNEL_TRANSACTION_FACTORY)));
    }
}