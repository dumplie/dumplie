<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\Extension;

use Dumplie\SharedKernel\Application\Command\Extension\Core\TransactionExtension;
use Dumplie\SharedKernel\Application\Command\ExtensionRegistry;
use Dumplie\SharedKernel\Application\Extension;
use Dumplie\SharedKernel\Application\ServiceContainer;
use Dumplie\SharedKernel\Application\ServiceLocator;

final class CoreExtension implements Extension
{
    const COMMAND_EXTENSION_REGISTRY_SERVICE_ID = 'dumplie.command.extension_registry';
    const TRANSACTION_FACTORY_SERVICE_ID = 'dumplie.transaction.factory';

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function configure(ServiceContainer $serviceContainer)
    {
        $locatorId = \Dumplie\SharedKernel\Infrastructure\Symfony\DependencyInjeciton\ServiceContainer::SERVICE_LOCATOR_ID;

        if (!$serviceContainer->definitionExists($locatorId)) {
            throw new \RuntimeException(sprintf('Service with id "%s" is missing in service container.', $locatorId));
        }

        $serviceContainer->register(self::COMMAND_EXTENSION_REGISTRY_SERVICE_ID, new ServiceContainer\Definition(
            ExtensionRegistry::class,
            [new ServiceContainer\ArgumentService($locatorId)]
        ));
    }

    /**
     * @param ServiceLocator $serviceLocator
     */
    public function boot(ServiceLocator $serviceLocator)
    {
        if (!$serviceLocator->has(self::TRANSACTION_FACTORY_SERVICE_ID)) {
            throw new \RuntimeException(sprintf('Service with id "%s" is missing in service locator.', self::TRANSACTION_FACTORY_SERVICE_ID));
        }

        $serviceLocator->get(self::COMMAND_EXTENSION_REGISTRY_SERVICE_ID)
            ->register(new TransactionExtension($serviceLocator->get(self::TRANSACTION_FACTORY_SERVICE_ID)));
    }
}