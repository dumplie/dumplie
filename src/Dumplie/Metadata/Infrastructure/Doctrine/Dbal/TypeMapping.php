<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Infrastructure\Doctrine\Dbal;

use Doctrine\DBAL\Schema\Table;
use Dumplie\Metadata\Schema\FieldDefinition;
use Dumplie\Metadata\Schema\Type;

interface TypeMapping
{
    /**
     * @param Type $type
     *
     * @return bool
     */
    public function maps(Type $type): bool;

    /**
     * @param string          $schema
     * @param Table           $table
     * @param string          $name
     * @param FieldDefinition $definition
     */
    public function map(string $schema, Table $table, string $name, FieldDefinition $definition);
}
