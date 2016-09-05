<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Tests\Integration\Infrastructure\Twig\UserInterface\View;

use Dumplie\SharedKernel\Application\View\ViewObject;

final class BarView implements ViewObject
{
    /**
     * @var FooView
     */
    private $foo;

    /**
     * @param FooView $foo
     */
    public function __construct(FooView $foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return FooView
     */
    public function foo() : FooView
    {
        return $this->foo;
    }

    public function name() : string
    {
        return 'bar';
    }

    public function context() : string
    {
        return 'test';
    }
}