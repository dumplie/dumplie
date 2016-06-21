<?php

declare (strict_types = 1);

namespace Dumplie\Application\Metadata;

use Dumplie\Application\Exception\Metadata\HydrationException;
use Dumplie\Application\Metadata\Schema\TypeSchema;

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