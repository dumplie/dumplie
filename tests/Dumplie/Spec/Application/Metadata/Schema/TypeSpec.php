<?php

namespace Spec\Dumplie\Application\Metadata\Schema;

use Dumplie\Application\Exception\Metadata\InvalidTypeException;
use Dumplie\Application\Metadata\Schema\Type;
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
