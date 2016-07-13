<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Infrastructure\Doctrine\Dbal\Field;

use Doctrine\DBAL\Schema\Table;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\DoctrineStorageException;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\TableName;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\TypeMapping;
use Dumplie\Metadata\Schema\AssociationFieldDefinition;
use Dumplie\Metadata\Schema\FieldDefinition;
use Dumplie\Metadata\Schema\Type;

class AssociationMapping implements TypeMapping
{
    use TableName;

    /**
     * @var Type
     */
    private $type;

    /**
     * TextMapping constructor.
     */
    public function __construct()
    {
        $this->type = Type::association();
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
     * @param string          $schema
     * @param Table           $table
     * @param string          $name
     * @param FieldDefinition $definition
     *
     * @throws DoctrineStorageException
     */
    public function map(string $schema, Table $table, string $name, FieldDefinition $definition)
    {
        if (!$definition instanceof AssociationFieldDefinition) {
            throw DoctrineStorageException::invalidDefinition(AssociationFieldDefinition::class, $definition);
        }

        $table->addColumn(
            $name,
            'guid',
            [
                'notnull' => !$definition->isNullable(),
                'default' => $definition->defaultValue(),
                'length' => $definition->options()['length'] ?? null,
                'unique' => $definition->options()['unique'] ?? false,
            ]
        );

        $table->addForeignKeyConstraint(
            $this->tableName($schema, $definition->typeSchema()->name()),
            [$name],
            ['id']
        );
    }
}
