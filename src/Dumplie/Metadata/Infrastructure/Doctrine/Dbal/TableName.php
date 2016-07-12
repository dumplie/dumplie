<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Infrastructure\Doctrine\Dbal;

trait TableName
{
    private $prefix = 'metadata';

    /**
     * @param string $schemaName
     * @param string $typeName
     *
     * @return string
     */
    protected function tableName(string $schemaName, string $typeName): string
    {
        return implode('_', [$this->prefix, $schemaName, $typeName]);
    }
}
