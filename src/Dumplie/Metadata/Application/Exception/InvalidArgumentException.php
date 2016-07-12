<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Application\Exception;

use Coduo\ToString\StringConverter;
use Dumplie\Metadata\Application\Metadata;
use Dumplie\Metadata\Application\MetadataId;
use Dumplie\Metadata\Application\Schema\TypeSchema;

class InvalidArgumentException extends Exception
{
    /**
     * @param string $fieldName
     * @param string $model
     * @return InvalidArgumentException
     */
    public static function fieldNameAlreadyExists(string $fieldName, string $model) : InvalidArgumentException
    {
        return new static(sprintf("Field with name \"%s\" already exists in model \"%s\"", $fieldName, $model));
    }

    /**
     * @param string $expectedType
     * @param $receivedValue
     * @return InvalidArgumentException
     */
    public static function expected(string $expectedType, $receivedValue) : InvalidArgumentException
    {
        return new static(sprintf(
            "Expected \"%s\" type but got \"%s\"",
            $expectedType,
            (string) new StringConverter($receivedValue)
        ));
    }

    /**
     * @param string $value
     * @return InvalidArgumentException
     */
    public static function emptyValue(string $value) : InvalidArgumentException
    {
        return new self(sprintf("Value \"%s\" can't be empty.", $value));
    }

    /**
     * @param string $modelName
     * @param string $field
     * @param MetadataId $id
     * @return InvalidArgumentException
     */
    public static function metadataFieldNotFound(string $modelName, string $field, MetadataId $id) : InvalidArgumentException
    {
        return new self(sprintf("Metadata field \"%s\" does not exists in \"%s\":\"%s\".", $field, $modelName, $id));
    }

    /**
     * @param string $expected
     * @param string $received
     * @return InvalidArgumentException
     */
    public static function unexpectedModel(string $expected, string $received) : InvalidArgumentException
    {
        return new self(sprintf("Expected model \"%s\" but received \"%s\".", $expected, $received));
    }

    /**
     * @param Metadata $metadata
     * @param TypeSchema $model
     * @return InvalidArgumentException
     */
    public static function invalidMetadata(Metadata $metadata, TypeSchema $model) : InvalidArgumentException
    {
        return new self(sprintf(
            "Metadata \"%s\":\"%s\" does not match model \"%s\".",
            $metadata->typeSchemaName(),
            (string) $metadata->id(),
            $model->name()
        ));
    }
}
