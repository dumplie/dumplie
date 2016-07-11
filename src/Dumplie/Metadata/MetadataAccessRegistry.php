<?php

declare (strict_types = 1);

namespace Dumplie\Metadata;

use Dumplie\SharedKernel\Application\Exception\Metadata\NotFoundException;

final class MetadataAccessRegistry
{
    /**
     * @var Schema
     */
    private $schema;

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
     * @param Schema $schema
     * @param Hydrator $hydrator
     */
    public function __construct(Storage $storage, Schema $schema, Hydrator $hydrator)
    {
        $this->schema = $schema;
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
        return new MetadataAccessObject(
            $this->storage,
            $this->schema->name(),
            $this->schema->get($typeName),
            $this->hydrator
        );
    }
}
