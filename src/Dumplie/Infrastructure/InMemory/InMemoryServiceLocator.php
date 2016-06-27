<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\InMemory;

use Dumplie\Application\Exception\NotFoundException;
use Dumplie\Application\ServiceLocator;

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