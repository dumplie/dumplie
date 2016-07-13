<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Infrastructure\Doctrine\Dbal;

use Dumplie\Metadata\Schema\FieldDefinition;
use Dumplie\Metadata\Schema\Type;

class DoctrineStorageException extends \Exception
{
    /**
     * @param string $table
     *
     * @return DoctrineStorageException
     */
    public static function tableAlreadyExists(string $table): DoctrineStorageException
    {
        return new static(sprintf('Table "%s" already exists', $table));
    }

    /**
     * @param Type $type
     *
     * @return DoctrineStorageException
     */
    public static function unableToMapType(Type $type): DoctrineStorageException
    {
        return new static(sprintf('Unable to map type schema "%s" to doctrine field type', (string) $type));
    }

    /**
     * @param string          $expectedClass
     * @param FieldDefinition $receivedInstance
     *
     * @return DoctrineStorageException
     */
    public static function invalidDefinition(string $expectedClass, FieldDefinition $receivedInstance): DoctrineStorageException
    {
        return new static(
            sprintf(
                'Invalid fielt type definition, expected "%s" got "%s"',
                (string) $expectedClass,
                get_class($receivedInstance)
            )
        );
    }
}
