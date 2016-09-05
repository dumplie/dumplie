<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\View;

interface ViewObject
{
    /**
     * @return string
     */
    public function name() : string;

    /**
     * @return string
     */
    public function context() : string;
}