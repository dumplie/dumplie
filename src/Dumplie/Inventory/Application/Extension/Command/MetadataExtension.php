<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Application\Extension\Command;

use Dumplie\Inventory\Application\Command\CreateProduct;
use Dumplie\Inventory\Application\Extension\Metadata as InventoryMetadata;
use Dumplie\Metadata\Metadata;
use Dumplie\Metadata\MetadataId;
use Dumplie\SharedKernel\Application\Command\Command;
use Dumplie\SharedKernel\Application\Command\Extension;
use Dumplie\SharedKernel\Application\ServiceLocator;
use Dumplie\SharedKernel\Application\Services;

final class MetadataExtension implements Extension
{
    /**
     * @param Command $command
     * @return bool
     */
    public function expands(Command $command) : bool
    {
        return $command instanceof CreateProduct;
    }

    /**
     * @param Command $command
     * @param ServiceLocator $serviceLocator
     */
    public function pre(Command $command, ServiceLocator $serviceLocator)
    {
    }

    /**
     * @param Command $command
     * @param ServiceLocator $serviceLocator
     */
    public function post(Command $command, ServiceLocator $serviceLocator)
    {
        /* @var CreateProduct $command */
        $serviceLocator->get(Services::KERNEL_METADATA_ACCESS_REGISTRY)->getMAO(InventoryMetadata::TYPE_NAME)->save(
            new Metadata(MetadataId::generate(), InventoryMetadata::TYPE_NAME, [
                InventoryMetadata::FIELD_SKU => $command->sku(),
                InventoryMetadata::FIELD_VISIBLE => false,
            ])
        );
    }

    /**
     * @param Command $command
     * @param \Exception $e
     * @param ServiceLocator $serviceLocator
     */
    public function catchException(Command $command, \Exception $e, ServiceLocator $serviceLocator)
    {
    }
}