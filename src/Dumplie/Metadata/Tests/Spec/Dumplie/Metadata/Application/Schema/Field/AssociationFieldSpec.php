<?php

namespace Spec\Dumplie\Metadata\Application\Schema\Field;

use Dumplie\Metadata\Application\Association;
use Dumplie\Metadata\Application\Metadata;
use Dumplie\Metadata\Application\MetadataId;
use Dumplie\Metadata\Application\Schema\FieldDefinition;
use Dumplie\Metadata\Application\Schema\TypeSchema;
use Dumplie\Metadata\Application\Exception\InvalidArgumentException;
use Dumplie\Metadata\Application\Exception\InvalidValueException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;

class AssociationFieldSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('foo', new TypeSchema('bar', []));
    }

    function it_is_model_type()
    {
        $this->shouldBeAnInstanceOf(FieldDefinition::class);
    }

    function it_requires_metadata_value_for_serialization()
    {
        $this->shouldThrow(InvalidValueException::class)->during("serialize", [new \stdClass()]);
    }

    function it_returns_null_during_deserialization_when_is_nullable()
    {
        $this->beConstructedWith('foo', new TypeSchema('bar', []), true);

        $this->deserialize(null)->shouldReturn(null);
    }

    function it_throws_exception_when_deserialized_value_is_not_empty_and_is_not_a_string()
    {
        $this->shouldThrow(InvalidArgumentException::class)->during("deserialize", [1]);
    }
    
    function it_deserializes_into_association()
    {
        $this->deserialize((string) Uuid::uuid4())->shouldReturnAnInstanceOf(Association::class);
    }

    function it_allows_null_value_when_nullable()
    {
        $this->beConstructedWith('foo', new TypeSchema('bar', []), true);

        $this->isValid(null)->shouldReturn(true);
    }

    function it_allows_metadata_value()
    {
        $metadata = new Metadata(MetadataId::generate(), 'bar');

        $this->isValid($metadata)->shouldReturn(true);
    }

    function it_does_not_allow_anything_else_than_metadata()
    {
        $this->isValid('foo bar')->shouldReturn(false);
    }

    function it_is_of_association_type()
    {
        $this->type()->__toString()->shouldReturn('association');
    }

    function it_has_options()
    {
        $this->beConstructedWith(
            'foo',
            new TypeSchema('bar', []),
            false,
            ['length' => 10, 'unique' => true, 'index' => true]
        );

        $this->options()->shouldReturn(['length' => 10, 'unique' => true, 'index' => true]);
    }
}
