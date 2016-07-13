<?php

namespace Spec\Dumplie\Metadata\Hydrator;

use Dumplie\Metadata\Schema\Field\TextField;
use Dumplie\Metadata\Schema\TypeSchema;
use Dumplie\Metadata\Storage;
use Dumplie\Metadata\Exception\HydrationException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;

class DefaultHydratorSpec extends ObjectBehavior
{
    function let(Storage $storage)
    {
        $this->beConstructedWith($storage);
    }

    function it_throws_exception_when_id_is_missing_in_data()
    {
        $schema = new TypeSchema(
            "product",
            [
            "id" => new TextField(),
            "sku" => new TextField(),
            "brand" => new TextField("Dumplie", true),
            "description" => new TextField()
        ]
        );

        $this->shouldThrow(HydrationException::class)->during("hydrate",[$schema, ["sku" => "DUMPLIE1"]]);
    }

    function it_hydrates_metadata_from_data_and_schema()
    {
        $schema = new TypeSchema(
            "product", 
            [
            "id" => new TextField(),
            "sku" => new TextField(),
            "brand" => new TextField("Dumplie", true),
            "description" => new TextField()
        ]
        );

        $metadata = $this->hydrate(
                $schema,
                [
                    "id" => (string) Uuid::uuid4(),
                    "sku" => "DUMPLIE_1",
                ]
        );

        $metadata->__get("sku")->shouldReturn("DUMPLIE_1");
        $metadata->__get("brand")->shouldReturn("Dumplie");
        $metadata->__get("description")->shouldReturn(null);
    }
}
