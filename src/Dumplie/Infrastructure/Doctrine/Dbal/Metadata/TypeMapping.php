<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\Doctrine\Dbal\Metadata;

use Doctrine\DBAL\Schema\Table;
use Dumplie\Application\Metadata\Schema\FieldDefinition;
use Dumplie\Application\Metadata\Schema\Type;

interface TypeMapping
{
    /**
     * @param Type $type
     *
     * @return bool
     */
    public function maps(Type $type): bool;

    /**
     * @param Table           $table
     * @param string          $name
     * @param FieldDefinition $definition
     */
    public function map(Table $table, string $name, FieldDefinition $definition);
}
