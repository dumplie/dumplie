<?php

namespace Spec\Dumplie\Metadata\Schema\Field;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BoolFieldSpec extends ObjectBehavior
{
    function it_can_serialize_bool_values()
    {
        $this->serialize(false)->shouldReturn("0");
        $this->serialize(true)->shouldReturn("1");
    }

    function it_can_deserialize_bool_values()
    {
        $this->deserialize("0")->shouldReturn(false);
        $this->deserialize("1")->shouldReturn(true);
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
