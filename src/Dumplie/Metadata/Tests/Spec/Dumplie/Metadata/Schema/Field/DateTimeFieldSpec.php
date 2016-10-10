<?php

namespace Spec\Dumplie\Metadata\Schema\Field;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DateTimeFieldSpec extends ObjectBehavior
{
    function it_can_serialize_datetime_values()
    {
        $this->serialize(new \DateTimeImmutable("2004-02-12T15:19:21", new \DateTimeZone('UTC')))
            ->shouldReturn("2004-02-12T15:19:21+00:00");
    }

    function it_can_deserialize_datetime_values()
    {
        $this->deserialize("2004-02-12T15:19:21+00:00")
            ->format("c")
            ->shouldReturn("2004-02-12T15:19:21+00:00");
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
