<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\InMemory;

use Dumplie\SharedKernel\Application\CommandBus\CommandHandlerMap;

final class InMemoryHandlerMap implements CommandHandlerMap
{
    /**
     * @var array
     */
    private $handlers;

    public function __construct()
    {
        $this->handlers = [];
    }

    /**
     * @param string $commandClass
     * @param $handler
     */
    public function register(string $commandClass, $handler)
    {
        $this->handlers[$commandClass] = $handler;
    }

    /**
     * @param string $commandClass
     * @return mixed
     */
    public function getHandlerFor(string $commandClass)
    {
        return $this->handlers[$commandClass];
    }
}