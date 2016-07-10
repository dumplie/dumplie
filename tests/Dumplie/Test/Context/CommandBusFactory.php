<?php

declare (strict_types = 1);

namespace Dumplie\Test\Context;

use Dumplie\Application\CommandBus;

interface CommandBusFactory
{
    /**
     * @param array $handlers
     * @return CommandBus|mixed
     */
    public function create(array $handlers = [], array $commandExtension = []) : CommandBus;
}