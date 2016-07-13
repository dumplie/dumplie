<?php

namespace Dumplie\Metadata\Tests\Integration\InMemory;

use Dumplie\Metadata\Infrastructure\InMemory\InMemoryStorage;
use Dumplie\Metadata\Storage;
use Dumplie\Metadata\Tests\Integration\Generic\MetadataTestCase;

class MetadataTest extends MetadataTestCase
{
    public function createStorage() : Storage
    {
        return new InMemoryStorage();
    }
}
