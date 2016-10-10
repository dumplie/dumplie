<?php

namespace Spec\Dumplie\Metadata\Schema\Field;

use Dumplie\Metadata\Exception\InvalidArgumentException;
use Dumplie\Metadata\Exception\InvalidValueException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FloatFieldSpec extends ObjectBehavior
{
    function it_can_serialize_float_values()
    {
        $this->serialize(123.456)->shouldReturn("123.456");
        $this->serialize(-123.456)->shouldReturn("-123.456");
    }

    function it_requires_float_for_serialization()
    {
        $this->shouldThrow(InvalidValueException::class)->during("serialize", [1]);
    }

    function it_can_deserialize_float_values()
    {
        $this->deserialize("123.456")->shouldReturn(123.456);
        $this->deserialize("-123.456")->shouldReturn(-123.456);
    }

    function it_throws_exception_when_serialized_value_is_not_empty_and_is_not_valid_string()
    {
        $this->shouldThrow(InvalidArgumentException::class)->during("deserialize", [new \stdClass()]);
    }

    function it_is_nullable_by_default()
    {
        $this->isNullable()->shouldReturn(true);
    }

    function it_has_default_value()
    {
        $this->defaultValue()->shouldReturn(null);
    }
}
