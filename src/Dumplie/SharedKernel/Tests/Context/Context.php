<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Tests\Context;

use Dumplie\SharedKernel\Application\CommandBus;

interface Context
{
    /**
     * @return CommandBus
     */
    public function commandBus() : CommandBus;
}