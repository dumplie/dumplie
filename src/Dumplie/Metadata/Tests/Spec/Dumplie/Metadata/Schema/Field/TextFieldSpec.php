<?php

namespace Spec\Dumplie\Metadata\Schema\Field;

use Dumplie\Metadata\Exception\InvalidArgumentException;
use Dumplie\Metadata\Exception\InvalidValueException;
use Dumplie\Metadata\Schema\FieldDefinition;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TextFieldSpec extends ObjectBehavior
{
    function it_is_model_type()
    {
        $this->shouldBeAnInstanceOf(FieldDefinition::class);
    }

    function it_requires_string_value_for_serialization()
    {
        $this->shouldThrow(InvalidValueException::class)->during("serialize", [new \DateTime()]);
    }

    function it_returns_null_during_deserialization_when_is_nullable()
    {
        $this->beConstructedWith(null, true);

        $this->deserialize(null)->shouldReturn(null);
    }

    function it_returns_default_value_during_empty_value_deserialization_if_its_set()
    {
        $this->beConstructedWith("default", true);

        $this->deserialize(null)->shouldReturn("default");
    }

    function it_throws_exception_when_deserialized_value_is_not_empty_and_is_not_valid_strign()
    {
        $this->shouldThrow(InvalidArgumentException::class)->during("deserialize", [new \DateTime()]);
    }

    function it_allows_null_value_when_nullable()
    {
        $this->beConstructedWith(null, true);

        $this->isValid(null)->shouldReturn(true);
    }

    function it_allows_any_string_value()
    {
        $this->isValid("test")->shouldReturn(true);
    }

    function it_does_not_allow_non_string_value()
    {
        $this->isValid(new \DateTime())->shouldReturn(false);
    }

    function it_is_of_text_type()
    {
        $this->type()->__toString()->shouldReturn('text');
    }

    function it_has_options()
    {
        $this->beConstructedWith(null, false, ['length' => 10, 'unique' => true, 'index' => true]);

        $this->options()->shouldReturn(['length' => 10, 'unique' => true, 'index' => true]);
    }
}
