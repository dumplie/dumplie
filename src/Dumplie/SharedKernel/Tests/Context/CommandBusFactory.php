<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Tests\Context;

use Dumplie\SharedKernel\Application\CommandBus;

interface CommandBusFactory
{
    /**
     * @param array $handlers
     * @return CommandBus|mixed
     */
    public function create(array $handlers = [], array $commandExtension = []) : CommandBus;
}