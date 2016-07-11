<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\Tactician;

use Dumplie\SharedKernel\Application\Command\Command;
use League\Tactician\CommandBus as Tactician;

final class CommandBus implements \Dumplie\SharedKernel\Application\CommandBus
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * CommandBus constructor.
     *
     * @param Tactician $commandBus
     */
    public function __construct(Tactician $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        $this->commandBus->handle($command);
    }
}
