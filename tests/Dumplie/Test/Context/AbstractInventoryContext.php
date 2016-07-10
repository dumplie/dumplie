<?php

declare (strict_types = 1);

namespace Dumplie\Test\Context;

use Dumplie\Application\Command\Inventory\CreateProduct;
use Dumplie\Application\Command\Inventory\CreateProductHandler;
use Dumplie\Application\Command\Inventory\PutBackProductToStock;
use Dumplie\Application\Command\Inventory\PutBackProductToStockHandler;
use Dumplie\Application\Command\Inventory\RemoveProductFromStock;
use Dumplie\Application\Command\Inventory\RemoveProductFromStockHandler;
use Dumplie\Application\CommandBus;
use Dumplie\Application\EventLog;
use Dumplie\Domain\Inventory\Products;

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