<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\Extension;

use Dumplie\Metadata\Hydrator\DefaultHydrator;
use Dumplie\Metadata\MetadataAccessRegistry;
use Dumplie\Metadata\Schema\Builder;
use Dumplie\SharedKernel\Application\Extension\Command\TransactionExtension;
use Dumplie\SharedKernel\Application\Command\ExtensionRegistry;
use Dumplie\SharedKernel\Application\Exception\ServiceContainer\ServiceNotFoundException;
use Dumplie\SharedKernel\Application\Extension;
use Dumplie\SharedKernel\Application\ServiceContainer;
use Dumplie\SharedKernel\Application\ServiceLocator;
use Dumplie\SharedKernel\Application\Services;
use Dumplie\SharedKernel\Infrastructure\InMemory\InMemoryHandlerMap;

final class CoreExtension implements Extension
{
    /**
     * @var string
     */
    private $storageServiceId;

    /**
     * @param string $storageServiceId
     */
    public function __construct(string $storageServiceId)
    {
        $this->storageServiceId = $storageServiceId;
    }

    /**
     * @return array
     */
    public function dependsOn() : array
    {
        return [];
    }

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function build(ServiceContainer $serviceContainer)
    {
        if (!$serviceContainer->definitionExists(Services::KERNEL_SERVICE_LOCATOR)) {
            throw new ServiceNotFoundException(sprintf('Service with id "%s" is missing in service container.', Services::KERNEL_SERVICE_LOCATOR));
        }

        $this->registerCommandServices($serviceContainer);
        $this->registerMetadataServices($serviceContainer);
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
            ->register(new TransactionExtension($serviceLocator->get(Services::KERNEL_TRANSACTION_FACTORY)), -1024);
    }

    /**
     * @param ServiceContainer $serviceContainer
     */
    protected function registerCommandServices(ServiceContainer $serviceContainer)
    {
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
     * @param ServiceContainer $serviceContainer
     */
    protected function registerMetadataServices(ServiceContainer $serviceContainer)
    {
        $serviceContainer->register(Services::KERNEL_METADATA_SCHEMA_BUILDER, new ServiceContainer\Definition(
            Builder::class,
            []
        ));

        $serviceContainer->register(Services::KERNEL_METADATA_HYDRATOR, new ServiceContainer\Definition(
            DefaultHydrator::class,
            [
                new ServiceContainer\ArgumentService($this->storageServiceId)
            ]
        ));

        $serviceContainer->register(Services::KERNEL_METADATA_ACCESS_REGISTRY, new ServiceContainer\Definition(
            MetadataAccessRegistry::class,
            [
                new ServiceContainer\ArgumentService($this->storageServiceId),
                new ServiceContainer\ArgumentService(Services::KERNEL_METADATA_SCHEMA_BUILDER),
                new ServiceContainer\ArgumentService(Services::KERNEL_METADATA_HYDRATOR)
            ]
        ));
    }
}