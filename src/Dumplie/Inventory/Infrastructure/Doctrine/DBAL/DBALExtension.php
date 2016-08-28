<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Infrastructure\Doctrine\DBAL;

use Dumplie\Inventory\Application\Services;
use Dumplie\Inventory\Infrastructure\Doctrine\DBAL\Query\DbalInventoryQuery;
use Dumplie\SharedKernel\Application\Exception\ServiceContainer\ServiceNotFoundException;
use Dumplie\SharedKernel\Application\Extension;
use Dumplie\SharedKernel\Application\ServiceContainer;
use Dumplie\SharedKernel\Application\ServiceLocator;

final class DBALExtension implements Extension
{
    /**
     * @var string
     */
    private $connectionServiceId;

    /**
     * @param string $connectionServiceId
     */
    public function __construct(string $connectionServiceId)
    {
        $this->connectionServiceId = $connectionServiceId;
    }

    /**
     * @param ServiceContainer $serviceContainer
     * @throws ServiceNotFoundException
     */
    public function configure(ServiceContainer $serviceContainer)
    {
        $serviceContainer->register(
            Services::INVENTORY_APPLICATION_QUERY,
            new ServiceContainer\Definition(DbalInventoryQuery::class, [
                new ServiceContainer\ArgumentService($this->connectionServiceId)
            ])
        );
    }

    public function boot(ServiceLocator $serviceLocator)
    {
    }
}