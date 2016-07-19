<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\InMemory;

use Dumplie\SharedKernel\Application\Exception\NotFoundException;
use Dumplie\SharedKernel\Application\ServiceLocator;
use Dumplie\SharedKernel\Application\ServiceContainer\Definition;

final class InMemoryServiceLocator implements ServiceLocator
{
    /**
     * @param string $id
     * @return mixed
     * @throws NotFoundException
     */
    public function get($id)
    {
        throw NotFoundException::serviceNotFound($id);
    }

    public function has($id)
    {
        return false;
    }

    public function register(string $id, Definition $definition)
    {
    }
}