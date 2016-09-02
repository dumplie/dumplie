<?php

declare (strict_types = 1);

namespace Dumplie\Metadata;

use Dumplie\Metadata\Exception\NotFoundException;
use Dumplie\Metadata\Schema\Builder;

final class MetadataAccessRegistry
{
    /**
     * @var Builder
     */
    private $schemaBuilder;

    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * @param Storage $storage
     * @param Builder $schemaBuilder
     * @param Hydrator $hydrator
     */
    public function __construct(Storage $storage, Builder $schemaBuilder, Hydrator $hydrator)
    {
        $this->schemaBuilder = $schemaBuilder;
        $this->storage = $storage;
        $this->hydrator = $hydrator;
    }

    /**
     * @param string $typeName
     * @return MetadataAccessObject
     * @throws NotFoundException
     */
    public function getMAO(string $typeName) : MetadataAccessObject
    {
        $schema = $this->schemaBuilder->build();

        return new MetadataAccessObject(
            $this->storage,
            $schema->name(),
            $schema->get($typeName),
            $this->hydrator
        );
    }
}
