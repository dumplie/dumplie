<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Tests;

use Dumplie\Inventory\Application\Command\CreateProduct;
use Dumplie\Inventory\Application\Command\CreateProductHandler;
use Dumplie\Inventory\Application\Command\PutBackProductToStock;
use Dumplie\Inventory\Application\Command\PutBackProductToStockHandler;
use Dumplie\Inventory\Application\Command\RemoveProductFromStock;
use Dumplie\Inventory\Application\Command\RemoveProductFromStockHandler;
use Dumplie\SharedKernel\Application\CommandBus;
use Dumplie\SharedKernel\Application\EventLog;
use Dumplie\Inventory\Domain\Products;
use Dumplie\SharedKernel\Tests\Context\CommandBusFactory;

abstract class AbstractInventoryContext implements InventoryContext
{
    /**
     * @var Products
     */
    protected $products;

    /**
     * @var CommandBusFactory
     */
    protected $commandBus;

    public function commandBus() : CommandBus
    {
        return $this->commandBus;
    }

    /**
     * @return Products
     */
    public function products() : Products
    {
        return $this->products;
    }

    /**
     * @param $sku
     * @param $price
     * @param string $currency
     * @param bool $available
     */
    public function addProduct(string $sku, int $price, string $currency = "EUR", bool $available = true)
    {
        $command = new CreateProduct($sku, $price, $currency, $available);

        $this->commandBus->handle($command);
    }

    /**
     * @param CommandBusFactory $commandBusFactory
     * @param EventLog $eventLog
     * @return CommandBus
     */
    protected function createCommandBus(CommandBusFactory $commandBusFactory, EventLog $eventLog, array $commandExtension = []) : CommandBus
    {
        return $commandBusFactory->create(
            [
                CreateProduct::class => new CreateProductHandler($this->products),
                PutBackProductToStock::class => new PutBackProductToStockHandler($this->products),
                RemoveProductFromStock::class => new RemoveProductFromStockHandler($this->products)
            ],
            $commandExtension
        );
    }
}