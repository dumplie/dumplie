<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Domain\Event;

use Dumplie\SharedKernel\Domain\Exception\UnknownEventException;

interface Listener
{
    /**
     * @param string $eventJson
     *
     * @throws UnknownEventException
     */
    public function on(string $eventJson);
}