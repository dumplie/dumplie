<?php

namespace Spec\Dumplie\Metadata\Application\Schema;

use Dumplie\Metadata\Application\Exception\InvalidTypeException;
use Dumplie\Metadata\Application\Schema\Type;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TypeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('text');
    }

    function it_requires_that_it_is_one_of_allowed_types()
    {
        $this->beConstructedWith('foo');

        $this->shouldThrow(InvalidTypeException::class)->duringInstantiation();
    }

    function is_equal_when_types_match()
    {
        $this->isEqual(new Type('text'))->shouldReturn(true);
    }
}
