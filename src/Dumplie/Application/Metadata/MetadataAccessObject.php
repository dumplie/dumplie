<?php

declare (strict_types = 1);

namespace Dumplie\Application\Metadata;

use Dumplie\Application\Exception\Metadata\HydrationException;
use Dumplie\Application\Exception\Metadata\InvalidArgumentException;
use Dumplie\Application\Exception\Metadata\NotFoundException;
use Dumplie\Application\Metadata\Schema\TypeSchema;
use Dumplie\Domain\SharedKernel\Exception\InvalidUUIDFormatException;

final class MetadataAccessObject
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var string
     */
    private $schema;

    /**
     * @var TypeSchema
     */
    private $typeSchema;

    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * @param Storage    $storage
     * @param string     $schema
     * @param TypeSchema $typeSchema
     * @param Hydrator   $hydrator
     */
    public function __construct(Storage $storage, string $schema, TypeSchema $typeSchema, Hydrator $hydrator)
    {
        $this->storage = $storage;
        $this->schema = $schema;
        $this->typeSchema = $typeSchema;
        $this->hydrator = $hydrator;
    }

    /**
     * @param array $criteria
     *
     * @return Metadata|null
     * @throws \Dumplie\Application\Exception\Metadata\HydrationException
     */
    public function findBy(array $criteria = [])
    {
        $data = $this->storage->findBy($this->schema, $this->typeSchema->name(), $criteria);

        if (count($data) === 0 || !array_key_exists('id', $data)) {
            return;
        }

        return $this->hydrator->hydrate($this->typeSchema, $data);
    }

    /**
     * @param array $criteria
     *
     * @return Metadata
     * @throws HydrationException
     * @throws InvalidUUIDFormatException
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    public function getBy(array $criteria = []) : Metadata
    {
        $metadata = $this->findBy($criteria);

        if ($metadata === null) {
            throw NotFoundException::metadataWithCriteria($criteria);
        }

        return $metadata;
    }

    /**
     * @param Metadata $metadata
     *
     * @throws InvalidArgumentException
     */
    public function save(Metadata $metadata)
    {
        if (!$metadata->isValid($this->typeSchema)) {
            throw InvalidArgumentException::invalidMetadata($metadata, $this->typeSchema);
        }

        $fieldsData = [];
        foreach ($metadata->fields() as $fieldName => $value) {
            $fieldDefinition = $this->typeSchema->getFieldDefinition($fieldName);

            $fieldsData[$fieldName] = $fieldDefinition->serialize($value);
        }

        $this->storage->save(
            $this->schema,
            $this->typeSchema->name(),
            (string) $metadata->id(),
            $fieldsData
        );
    }

    /**
     * @param MetadataId $id
     *
     * @throws NotFoundException
     */
    public function delete(MetadataId $id)
    {
        if (!$this->storage->has($this->schema, $this->typeSchema->name(), (string) $id)) {
            throw NotFoundException::metadata($id);
        }

        $this->storage->delete($this->schema, $this->typeSchema->name(), (string) $id);
    }
}
