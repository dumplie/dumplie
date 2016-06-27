<?php

declare (strict_types = 1);

namespace Dumplie\Application;

use Dumplie\Application\Exception\NotFoundException;

interface ServiceLocator
{
    /**
     * @param string $id
     * @return mixed
     * @throws NotFoundException
     */
    public function get(string $id);
}