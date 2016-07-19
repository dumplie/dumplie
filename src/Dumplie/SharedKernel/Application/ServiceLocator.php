<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application;

use Dumplie\SharedKernel\Application\Exception\NotFoundException;

interface ServiceLocator
{
    /**
     * @param string $id
     * @return mixed
     * @throws NotFoundException
     */
    public function get($id);

    /**
     * @param string $id
     * @return bool
     */
    public function has($id);
}