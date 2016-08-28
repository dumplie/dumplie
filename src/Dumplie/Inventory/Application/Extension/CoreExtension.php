<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Application\Extension;

use Dumplie\Inventory\Application\Command\CreateProduct;
use Dumplie\Inventory\Application\Command\CreateProductHandler;
use Dumplie\SharedKernel\Application\Extension;
use Dumplie\SharedKernel\Application\ServiceContainer;
use Dumplie\SharedKernel\Application\ServiceLocator;
use Dumplie\Inventory\Application\Services as InventoryServices;
use Dumplie\SharedKernel\Application\Services;

final class CoreExtension implements Extension
{
    /**
     * @param ServiceContainer $serviceContainer
     */
    public function configure(ServiceContainer $serviceContainer)
    {
        $serviceContainer->register(
            InventoryServices::INVENTORY_COMMAND_CREATE_PRODUCT_HANDLER,
            new ServiceContainer\Definition(
                CreateProductHandler::class,
                [
                    new ServiceContainer\ArgumentService(InventoryServices::INVENTORY_DOMAIN_PRODUCTS)
                ]
            )
        );
    }

    /**
     * @param ServiceLocator $serviceLocator
     */
    public function boot(ServiceLocator $serviceLocator)
    {
        $serviceLocator->get(Services::KERNEL_COMMAND_HANDLER_MAP)->register(
            CreateProduct::class,
            $serviceLocator->get(InventoryServices::INVENTORY_COMMAND_CREATE_PRODUCT_HANDLER)
        );
    }
}