<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Infrastructure\Doctrine\ORM;

use Dumplie\Inventory\Infrastructure\Doctrine\ORM\Domain\ORMProducts;
use Dumplie\SharedKernel\Application\Exception\ServiceContainer\ServiceNotFoundException;
use Dumplie\SharedKernel\Application\ServiceContainer\ArgumentService;
use Dumplie\SharedKernel\Application\ServiceContainer\Definition;
use Dumplie\SharedKernel\Application\ServiceLocator;
use Dumplie\SharedKernel\Infrastructure\Symfony\DependencyInjeciton\ServiceContainer;

final class ORMExtension
{
    const INVENTORY_PRODUCTS_SERVICE_ID = 'dumplie.inventory.products';

    /**
     * @var string
     */
    private $entityManagerServiceId;

    /**
     * @param string $entityManagerServiceId
     */
    public function __construct(string $entityManagerServiceId)
    {
        $this->entityManagerServiceId = $entityManagerServiceId;
    }

    /**
     * @param ServiceContainer $serviceContainer
     * @throws ServiceNotFoundException
     */
    public function configure(ServiceContainer $serviceContainer)
    {
        $serviceContainer->register(
            self::INVENTORY_PRODUCTS_SERVICE_ID,
            new Definition(
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