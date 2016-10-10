<?php

namespace Spec\Dumplie\Metadata\Schema\Field;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MapFieldSpec extends ObjectBehavior
{
    function it_can_serialize_map_values()
    {
        $this->serialize([])->shouldReturn("[]");
        $this->serialize([1, 'foo' => [2, 'bar']])->shouldReturn("{\"0\":1,\"foo\":[2,\"bar\"]}");
    }

    function it_can_deserialize_map_values()
    {
        $this->deserialize("[]")->shouldReturn([]);
        $this->deserialize("{\"0\":1,\"foo\":[2,\"bar\"]}")->shouldReturn([1, 'foo' => [2, 'bar']]);
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
