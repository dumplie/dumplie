<?php

namespace Spec\Dumplie\Application\Metadata;

use Dumplie\Application\Exception\Metadata\NotFoundException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SchemaSpec extends ObjectBehavior
{
    function it_throws_exception_when_model_does_not_exists()
    {
        $this->shouldThrow(NotFoundException::class)->during("get", ["product"]);
    }
}
