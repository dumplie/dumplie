<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Infrastructure\Doctrine\ORM;

use Dumplie\Inventory\Application\Services;
use Dumplie\Inventory\Infrastructure\Doctrine\ORM\Domain\ORMProducts;
use Dumplie\SharedKernel\Application\Exception\ServiceContainer\ServiceNotFoundException;
use Dumplie\SharedKernel\Application\ServiceContainer\ArgumentService;
use Dumplie\SharedKernel\Application\ServiceContainer\Definition;
use Dumplie\SharedKernel\Application\ServiceLocator;
use Dumplie\SharedKernel\Infrastructure\Symfony\DependencyInjeciton\ServiceContainer;

final class ORMExtension
{
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
            Services::INVENTORY_DOMAIN_PRODUCTS,
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