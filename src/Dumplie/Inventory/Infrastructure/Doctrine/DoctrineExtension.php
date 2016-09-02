<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Infrastructure\Doctrine;

use Dumplie\Inventory\Application\Extension\CoreExtension;
use Dumplie\Inventory\Application\Services;
use Dumplie\Inventory\Infrastructure\Doctrine\DBAL\Query\DbalInventoryQuery;
use Dumplie\Inventory\Infrastructure\Doctrine\ORM\Domain\ORMProducts;
use Dumplie\SharedKernel\Application\Extension;
use Dumplie\SharedKernel\Application\ServiceContainer;
use Dumplie\SharedKernel\Application\ServiceContainer\ArgumentService;
use Dumplie\SharedKernel\Application\ServiceLocator;

final class DoctrineExtension implements Extension
{
    /**
     * @var string
     */
    private $entityManagerServiceId;

    /**
     * @var string
     */
    private $connectionServiceId;

    /**
     * @param string $entityManagerServiceId
     * @param string $connectionServiceId
     */
    public function __construct(string $entityManagerServiceId, string $connectionServiceId)
    {
        $this->connectionServiceId = $connectionServiceId;
        $this->entityManagerServiceId = $entityManagerServiceId;
    }

    public function dependsOn() : array
    {
        return [
            CoreExtension::class,
            Extension\CoreExtension::class
        ];
    }

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function build(ServiceContainer $serviceContainer)
    {
        $serviceContainer->register(
            Services::INVENTORY_APPLICATION_QUERY,
            new ServiceContainer\Definition(DbalInventoryQuery::class, [
                new ServiceContainer\ArgumentService($this->connectionServiceId),
                new ServiceContainer\ArgumentService(\Dumplie\SharedKernel\Application\Services::KERNEL_METADATA_ACCESS_REGISTRY)
            ])
        );

        $serviceContainer->register(
            Services::INVENTORY_DOMAIN_PRODUCTS,
            new ServiceContainer\Definition(
                ORMProducts::class,
                [
                    new ArgumentService($this->entityManagerServiceId)
                ]
            )
        );
    }

    public function boot(ServiceLocator $serviceLocator)
    {
    }
}