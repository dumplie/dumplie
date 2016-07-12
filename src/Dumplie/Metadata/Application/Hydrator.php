<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Application;

use Dumplie\Metadata\Application\Exception\HydrationException;
use Dumplie\Metadata\Application\Schema\TypeSchema;

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
