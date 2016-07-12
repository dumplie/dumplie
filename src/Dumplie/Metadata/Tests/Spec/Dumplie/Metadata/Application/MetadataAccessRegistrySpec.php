<?php

namespace Spec\Dumplie\Metadata\Application;

use Dumplie\Metadata\Application\Hydrator;
use Dumplie\Metadata\Application\MetadataAccessObject;
use Dumplie\Metadata\Application\Schema\Field\TextField;
use Dumplie\Metadata\Application\Schema\TypeSchema;
use Dumplie\Metadata\Application\Schema;
use Dumplie\Metadata\Application\Storage;
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

