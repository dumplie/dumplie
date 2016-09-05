<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\UserInterface\View;

use Dumplie\Inventory\UserInterface\Views;
use Dumplie\SharedKernel\Application\View\ViewObject;

abstract class AbstractView implements ViewObject
{
    /**
     * @return string
     */
    public function name() : string
    {
        return lcfirst(basename((new \ReflectionClass($this))->getFileName(), '.php'));
    }

    /**
     * @return string
     */
    public function context() : string
    {
        return Views::CONTEXT;
    }
}