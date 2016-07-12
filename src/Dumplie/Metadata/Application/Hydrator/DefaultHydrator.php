<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Application\Hydrator;

use Dumplie\Metadata\Application\Association;
use Dumplie\Metadata\Application\Hydrator;
use Dumplie\Metadata\Application\Metadata;
use Dumplie\Metadata\Application\MetadataId;
use Dumplie\Metadata\Application\Schema\TypeSchema;
use Dumplie\Metadata\Application\Storage;
use Dumplie\Metadata\Application\Exception\HydrationException;

final class DefaultHydrator implements Hydrator
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * DefaultHydrator constructor.
     *
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param TypeSchema $type
     * @param array      $data
     *
     * @return Metadata
     * @throws HydrationException
     */
    public function hydrate(TypeSchema $type, array $data = []) : Metadata
    {
        if (!array_key_exists('id', $data)) {
            throw HydrationException::missingId();
        }

        $id = new MetadataId($data['id']);
        unset($data['id']);

        $fieldValues = [];
        foreach ($data as $key => $value) {
            $field = $type->getFieldDefinition($key);
            $value = $field->deserialize($value);

            if ($value instanceof Association) {
                $value = $this->associate($value);
            }

            $fieldValues[$key] = $value;
        }

        foreach ($type->getDefinitions(array_keys($data)) as $key => $field) {
            $fieldValues[$key] = $field->defaultValue();
        }

        return new Metadata($id, $type->name(), $fieldValues);
    }

    /**
     * @param Association $association
     *
     * @return Metadata
     * @throws HydrationException
     */
    private function associate(Association $association): Metadata
    {
        $data = $this->storage->findBy(
            $association->schema(),
            $association->type()->name(),
            $association->criteria()
        );

        return $this->hydrate($association->type(), $data);
    }
}
