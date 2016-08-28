<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\Tactician;

use Dumplie\SharedKernel\Application\CommandBus\CommandHandlerMap;
use League\Tactician\Handler\Locator\HandlerLocator;

final class MapLocator implements HandlerLocator
{
    /**
     * @var CommandHandlerMap
     */
    private $map;

    /**
     * @param CommandHandlerMap $map
     */
    public function __construct(CommandHandlerMap $map)
    {
        $this->map = $map;
    }

    /**
     * @param string $commandName
     * @return mixed
     */
    public function getHandlerForCommand($commandName)
    {
        return $this->map->getHandlerFor($commandName);
    }
}