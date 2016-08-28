<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\CommandBus;

interface CommandHandlerMap
{
    /**
     * @param string $commandClass
     * @param $handler
     */
    public function register(string $commandClass, $handler);

    /**
     * @param string $commandClass
     * @return mixed
     */
    public function getHandlerFor(string $commandClass);
}