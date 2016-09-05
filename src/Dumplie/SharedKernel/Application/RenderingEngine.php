<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application;

use Dumplie\SharedKernel\Application\View\ViewObject;

interface RenderingEngine
{
    /**
     * @param ViewObject $viewObject
     * @param string $context
     * @param string $format
     * @return string
     */
    public function render(ViewObject $viewObject, string $context = null, string $format = 'html') : string;
}