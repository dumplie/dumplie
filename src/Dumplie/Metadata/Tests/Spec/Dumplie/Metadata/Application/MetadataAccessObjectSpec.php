<?php

namespace Spec\Dumplie\Metadata\Application;

use Dumplie\Metadata\Application\Schema\Field\TextField;
use Dumplie\Metadata\Application\Schema\TypeSchema;
use Dumplie\Metadata\Application\Storage;
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
