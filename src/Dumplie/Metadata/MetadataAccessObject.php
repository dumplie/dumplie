<?php

declare (strict_types = 1);

namespace Dumplie\Metadata;

use Dumplie\Metadata\Exception\HydrationException;
use Dumplie\Metadata\Exception\InvalidArgumentException;
use Dumplie\Metadata\Exception\NotFoundException;
use Dumplie\Metadata\Schema\AssociationFieldDefinition;
use Dumplie\Metadata\Schema\TypeSchema;
use Dumplie\SharedKernel\Domain\Exception\InvalidUUIDFormatException;

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
     * @return TypeSchema
     */
    public function typeSchema() : TypeSchema
    {
        return $this->typeSchema;
    }

    /**
     * @param array $criteria
     *
     * @return Metadata|null
     * @throws \Dumplie\Metadata\Exception\HydrationException
     */
    public function findBy(array $criteria = [])
    {
        $data = $this->storage->findBy($this->schema, $this->typeSchema->name(), $criteria);

        if (count($data) === 0 || !array_key_exists('id', $data)) {
            return null;
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

        $this->saveTree($metadata, $this->schema, $this->typeSchema);
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

    /**
     * @param Metadata   $metadata
     * @param string     $schema
     * @param TypeSchema $typeSchema
     */
    private function saveTree(Metadata $metadata, string $schema, TypeSchema $typeSchema)
    {
        $fieldsData = [];
        foreach ($metadata->fields() as $fieldName => $value) {
            $fieldDefinition = $typeSchema->getFieldDefinition($fieldName);

            if ($value === null) {
                $fieldsData[$fieldName] = null;
                continue;
            }

            if ($fieldDefinition instanceof AssociationFieldDefinition) {
                $this->saveTree($value, $fieldDefinition->schema(), $fieldDefinition->typeSchema());
            }

            $fieldsData[$fieldName] = $fieldDefinition->serialize($value);
        }

        $this->storage->save(
            $schema,
            $typeSchema->name(),
            (string) $metadata->id(),
            $fieldsData
        );
    }
}
