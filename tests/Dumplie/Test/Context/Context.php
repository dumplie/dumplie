<?php

declare (strict_types = 1);

namespace Dumplie\Test\Context;

use Dumplie\Application\CommandBus;

interface Context
{
    /**
     * @return CommandBus
     */
    public function commandBus() : CommandBus;
}