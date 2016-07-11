<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Domain\Event;

interface Event
{
    /**
     * @return \DateTimeImmutable
     */
    public function date() : \DateTimeImmutable;
}