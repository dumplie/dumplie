<?php

declare (strict_types = 1);

namespace Dumplie\Domain\SharedKernel\Event;

use Dumplie\Domain\SharedKernel\Exception\UnknownEventException;

interface Listener
{
    /**
     * @param string $eventJson
     *
     * @throws UnknownEventException
     */
    public function on(string $eventJson);
}