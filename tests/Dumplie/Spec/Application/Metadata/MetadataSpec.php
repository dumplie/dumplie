<?php

namespace Spec\Dumplie\Application\Metadata;

use Dumplie\Application\Exception\Metadata\InvalidArgumentException;
use Dumplie\Application\Metadata\Metadata;
use Dumplie\Application\Metadata\MetadataId;
use Dumplie\Application\Metadata\Schema\Field\TextField;
use Dumplie\Application\Metadata\Schema\TypeSchema;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MetadataSpec extends ObjectBehavior
{
    function it_throws_exception_when_model_name_is_empty()
    {
        $this->shouldThrow(InvalidArgumentException::class)->during("__construct", [MetadataId::generate(), ""]);
    }

    function it_allows_fields_to_be_accessed_by_magic_get()
    {
        $this->beConstructedWith(MetadataId::generate(), "product", ["sku" => "DUMPLIE_SKU_1"]);

        $this->__get('sku')->shouldReturn("DUMPLIE_SKU_1");
    }

    function it_allows_fields_to_be_modified_by_magic_set()
    {
        $this->beConstructedWith(MetadataId::generate(), "product", ["sku" => "DUMPLIE_SKU_1"]);

        $this->__set('sku', "NEW_SKU_1");

        $this->__get('sku')->shouldReturn("NEW_SKU_1");
    }

    function it_throws_exception_when_accessing_not_existing_metadata_field()
    {
        $this->beConstructedWith(MetadataId::generate(), "product", ["sku" => "DUMPLIE_SKU_1"]);

        $this->shouldThrow(InvalidArgumentException::class)->during("__get", ["name"]);
    }

    function it_can_be_validated_against_model()
    {
        $this->beConstructedWith(MetadataId::generate(), "product", ["sku" => "DUMPLIE_SKU_1"]);

        $this->isValid(new TypeSchema("product", ["sku" => new TextField()]))->shouldReturn(true);
    }

    function it_throws_exception_when_validated_for_wrong_model()
    {
        $this->beConstructedWith(MetadataId::generate(), "product", ["sku" => "DUMPLIE_SKU_1"]);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during("isValid", [new TypeSchema("order", ["user_email" => new TextField()])]);
    }
}
