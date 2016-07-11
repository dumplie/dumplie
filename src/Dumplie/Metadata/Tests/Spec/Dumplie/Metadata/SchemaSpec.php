<?php

namespace Spec\Dumplie\Metadata;

use Dumplie\SharedKernel\Application\Exception\Metadata\NotFoundException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SchemaSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('schema');
    }

    function it_throws_exception_when_model_does_not_exists()
    {
        $this->shouldThrow(NotFoundException::class)->during("get", ["product"]);
    }
}
