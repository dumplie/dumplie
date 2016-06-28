<?php

namespace Spec\Dumplie\Application\Metadata;

use Dumplie\Application\Metadata\Hydrator;
use Dumplie\Application\Metadata\MetadataAccessObject;
use Dumplie\Application\Metadata\Schema\Field\TextField;
use Dumplie\Application\Metadata\Schema\TypeSchema;
use Dumplie\Application\Metadata\Schema;
use Dumplie\Application\Metadata\Storage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MetadataAccessRegistrySpec extends ObjectBehavior
{
    function let(Storage $storage, Hydrator $hydrator)
    {
        $schema = new Schema('schema');
        $schema->add(new TypeSchema("product", ["sku" => new TextField()]));
        $this->beConstructedWith($storage, $schema, $hydrator);
    }

    function it_returns_MAO_for_existing_type_schema()
    {
        $this->getMAO("product")->shouldReturnAnInstanceOf(MetadataAccessObject::class);
    }
}

