<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\Tactician;

use League\Tactician\CommandBus as Tactician;

final class CommandBus implements \Dumplie\Application\CommandBus
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
     * @param $command
     */
    public function handle($command)
    {
        $this->commandBus->handle($command);
    }
}
