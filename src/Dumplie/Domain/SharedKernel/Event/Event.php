<?php

declare (strict_types = 1);

namespace Dumplie\Domain\SharedKernel\Event;

interface Event
{
    /**
     * @return \DateTimeImmutable
     */
    public function date() : \DateTimeImmutable;
}