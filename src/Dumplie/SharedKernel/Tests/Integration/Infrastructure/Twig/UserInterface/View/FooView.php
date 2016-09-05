<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Tests\Integration\Infrastructure\Twig\UserInterface\View;

use Dumplie\SharedKernel\Application\View\ViewObject;

final class FooView implements ViewObject
{
    public function foo() : string
    {
        return 'foo';
    }

    public function name() : string
    {
        return 'foo';
    }

    public function context() : string
    {
        return 'test';
    }
}