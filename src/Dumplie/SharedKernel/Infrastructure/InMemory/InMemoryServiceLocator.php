<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\InMemory;

use Dumplie\SharedKernel\Application\Exception\NotFoundException;
use Dumplie\SharedKernel\Application\ServiceLocator;

final class InMemoryServiceLocator implements ServiceLocator
{
    /**
     * @param string $id
     * @return mixed
     * @throws NotFoundException
     */
    public function get(string $id)
    {
        throw NotFoundException::serviceNotFound($id);
    }
}