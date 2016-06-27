<?php

declare (strict_types = 1);

namespace Dumplie\Application;

use Dumplie\Application\Command\Command;

interface CommandBus
{
    /**
     * @param Command $command
     */
    public function handle(Command $command);
}
