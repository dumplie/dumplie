<?php

namespace Spec\Dumplie\Metadata\Schema\Field;

use Dumplie\Metadata\Exception\InvalidArgumentException;
use Dumplie\Metadata\Exception\InvalidValueException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IntegerFieldSpec extends ObjectBehavior
{
    function it_can_serialize_integer_values()
    {
        $this->serialize(-1)->shouldReturn("-1");
        $this->serialize(0)->shouldReturn("0");
        $this->serialize(1)->shouldReturn("1");
    }

    function it_requires_integer_for_serialization()
    {
        $this->shouldThrow(InvalidValueException::class)->during("serialize", [123.456]);
    }

    function it_can_deserialize_integer_values()
    {
        $this->deserialize("-1")->shouldReturn(-1);
        $this->deserialize("0")->shouldReturn(0);
        $this->deserialize("1")->shouldReturn(1);
    }

    function it_throws_exception_when_serialized_value_is_not_empty_and_is_not_valid_string()
    {
        $this->shouldThrow(InvalidArgumentException::class)->during("deserialize", [new \DateTime()]);
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
