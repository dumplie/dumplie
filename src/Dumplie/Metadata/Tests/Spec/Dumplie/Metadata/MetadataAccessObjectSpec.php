<?php

namespace Spec\Dumplie\Metadata;

use Dumplie\Metadata\Schema\Field\TextField;
use Dumplie\Metadata\Schema\TypeSchema;
use Dumplie\Metadata\Storage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MetadataAccessObjectSpec extends ObjectBehavior
{
    function let(Storage $storage)
    {
        $type = new TypeSchema("product", [
            "sku" => new TextField()
        ]);
        $this->beConstructedWith($storage, $type);
    }
}
