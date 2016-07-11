<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application;

use Dumplie\SharedKernel\Application\Command\Command;

interface CommandBus
{
    /**
     * @param Command $command
     */
    public function handle(Command $command);
}
