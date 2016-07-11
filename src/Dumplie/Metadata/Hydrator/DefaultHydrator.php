<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Hydrator;

use Dumplie\SharedKernel\Application\Exception\Metadata\HydrationException;
use Dumplie\SharedKernel\Application\Exception\Metadata\InvalidArgumentException;
use Dumplie\Metadata\Hydrator;
use Dumplie\Metadata\Metadata;
use Dumplie\Metadata\MetadataId;
use Dumplie\Metadata\Schema\TypeSchema;
use Dumplie\SharedKernel\Domain\Exception\InvalidUUIDFormatException;

final class DefaultHydrator implements Hydrator
{
    /**
     * @param TypeSchema $schema
     * @param array $data
     * @return Metadata
     * @throws InvalidArgumentException
     * @throws InvalidUUIDFormatException
     * @throws HydrationException
     */
    public function hydrate(TypeSchema $schema, array $data = []) : Metadata
    {
        if (!array_key_exists('id', $data)) {
            throw HydrationException::missingId();
        }

        $id = new MetadataId($data['id']);
        unset($data['id']);

        $fieldValues = [];
        foreach ($data as $key => $value) {
            $field = $schema->getFieldDefinition($key);

            $fieldValues[$key] = $field->deserialize($value);
        }

        foreach ($schema->getDefinitions(array_keys($data)) as $key => $field) {
            $fieldValues[$key] = $field->defaultValue();
        }

        return new Metadata($id, $schema->name(), $fieldValues);
    }
}