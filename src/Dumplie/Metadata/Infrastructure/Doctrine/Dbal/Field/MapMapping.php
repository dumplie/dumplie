<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Infrastructure\Doctrine\Dbal\Field;

use Doctrine\DBAL\Schema\Table;
use Dumplie\Metadata\Schema\FieldDefinition;
use Dumplie\Metadata\Schema\Type;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\TypeMapping;

class MapMapping implements TypeMapping
{
    /**
     * @param Type $type
     *
     * @return bool
     */
    public function maps(Type $type): bool
    {
        return Type::map()->isEqual($type);
    }

    /**
     * @param string          $schema
     * @param Table           $table
     * @param string          $name
     * @param FieldDefinition $definition
     */
    public function map(string $schema, Table $table, string $name, FieldDefinition $definition)
    {
        $table->addColumn(
            $name,
            'json_array',
            [
                'notnull' => !$definition->isNullable(),
                'unique' => $definition->options()['unique'] ?? false,
            ]
        );

        if ($definition->options()['index'] ?? false) {
            $table->addIndex([$name]);
        }
    }
}
