<?php

declare (strict_types = 1);

namespace Dumplie\Metadata;

use Dumplie\Metadata\Exception\HydrationException;
use Dumplie\Metadata\Schema\TypeSchema;

interface Hydrator
{
    /**
     * @param TypeSchema $schema
     * @param array $data
     * @return Metadata
     * @throws HydrationException
     */
    public function hydrate(TypeSchema $schema, array $data = []) : Metadata;
}
