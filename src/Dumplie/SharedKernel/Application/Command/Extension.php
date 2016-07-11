<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\Command;

use Dumplie\SharedKernel\Application\ServiceLocator;

interface Extension
{
    /**
     * @param Command $command
     * @return bool
     */
    public function expands(Command $command) : bool;

    /**
     * @param Command $command
     * @param ServiceLocator $serviceLocator
     */
    public function pre(Command $command, ServiceLocator $serviceLocator);

    /**
     * @param Command $command
     * @param ServiceLocator $serviceLocator
     */
    public function post(Command $command, ServiceLocator $serviceLocator);

    /**
     * @param Command $command
     * @param \Exception $e
     * @param ServiceLocator $serviceLocator
     */
    public function catchException(Command $command, \Exception $e, ServiceLocator $serviceLocator);
}