<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Infrastructure\Doctrine\Dbal\Field;

use Doctrine\DBAL\Schema\Table;
use Dumplie\Metadata\Schema\FieldDefinition;
use Dumplie\Metadata\Schema\Type;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\TypeMapping;

class TextMapping implements TypeMapping
{
    /**
     * @var Type
     */
    private $type;

    /**
     * TextMapping constructor.
     */
    public function __construct()
    {
        $this->type = new Type(Type::TYPE_TEXT);
    }

    /**
     * @param Type $type
     *
     * @return bool
     */
    public function maps(Type $type): bool
    {
        return $this->type->isEqual($type);
    }

    /**
     * @param Table           $table
     * @param string          $name
     * @param FieldDefinition $definition
     *
     * @return bool
     */
    public function map(Table $table, string $name, FieldDefinition $definition): bool
    {
        $table->addColumn(
            $name,
            'string',
            [
                'notnull' => !$definition->isNullable(),
                'default' => $definition->defaultValue(),
                'length' => $definition->options()['length'] ?? null,
                'unique' => $definition->options()['unique'] ?? false,
            ]
        );

        if ($definition->options()['index'] ?? false) {
            $table->addIndex([$name]);
        }

        return true;
    }
}
