<?php

namespace Spec\Dumplie\SharedKernel\Application\View;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContextMapSpec extends ObjectBehavior
{
    function it_throws_exception_when_context_is_already_extended()
    {
        $this->extendContext('context', 'new');

        $this->shouldThrow(\RuntimeException::class)->during('extendContext', ['context', 'new_test']);
    }

    function it_knows_when_context_is_extended()
    {
        $this->isExtended('context')->shouldReturn(false);

        $this->extendContext('context', 'new');

        $this->isExtended('context')->shouldReturn(true);
    }
}
