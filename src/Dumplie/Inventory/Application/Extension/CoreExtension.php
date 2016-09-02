<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Application\Extension;

use Dumplie\Inventory\Application\Command\CreateProduct;
use Dumplie\Inventory\Application\Command\CreateProductHandler;
use Dumplie\Inventory\Application\Extension\Command\MetadataExtension;
use Dumplie\Metadata\Schema\Field\BoolField;
use Dumplie\Metadata\Schema\Field\TextField;
use Dumplie\Metadata\Schema\TypeSchema;
use Dumplie\SharedKernel\Application\Extension;
use Dumplie\SharedKernel\Application\ServiceContainer;
use Dumplie\SharedKernel\Application\ServiceLocator;
use Dumplie\Inventory\Application\Services as InventoryServices;
use Dumplie\SharedKernel\Application\Services;

final class CoreExtension implements Extension
{
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
        $this->registerCommandHandler($serviceContainer);
    }

    /**
     * @param ServiceLocator $serviceLocator
     */
    public function boot(ServiceLocator $serviceLocator)
    {
        $this->mapCommands($serviceLocator);

        $this->updateMetadataSchema($serviceLocator);

        $this->registerCommandExtensions($serviceLocator);
    }

    /**
     * @param ServiceLocator $serviceLocator
     */
    protected function mapCommands(ServiceLocator $serviceLocator)
    {
        $serviceLocator->get(Services::KERNEL_COMMAND_HANDLER_MAP)->register(
            CreateProduct::class,
            $serviceLocator->get(InventoryServices::INVENTORY_COMMAND_CREATE_PRODUCT_HANDLER)
        );
    }

    /**
     * @param ServiceLocator $serviceLocator
     */
    protected function updateMetadataSchema(ServiceLocator $serviceLocator)
    {
        $serviceLocator->get(Services::KERNEL_METADATA_SCHEMA_BUILDER)
            ->addType(new TypeSchema(Metadata::TYPE_NAME, [
                Metadata::FIELD_SKU => new TextField(),
                Metadata::FIELD_VISIBLE => new BoolField(false, false)
            ]));
    }

    /**
     * @param ServiceLocator $serviceLocator
     */
    protected function registerCommandExtensions(ServiceLocator $serviceLocator)
    {
        $serviceLocator->get(Services::KERNEL_COMMAND_EXTENSION_REGISTRY)->register(new MetadataExtension());
    }

    /**
     * @param ServiceContainer $serviceContainer
     */
    protected function registerCommandHandler(ServiceContainer $serviceContainer)
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
}