<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\Doctrine\Dbal\Metadata;

use Doctrine\DBAL\Schema\Table;
use Dumplie\Application\Metadata\Schema\FieldDefinition;
use Dumplie\Infrastructure\Doctrine\Dbal\Metadata\Field\TextMapping;

final class TypeRegistry
{
    /**
     * @var TypeMapping[]
     */
    private $mapping;

    /**
     * TypeRegistry constructor.
     *
     * @param array $typeMappings
     */
    public function __construct(array $typeMappings = [])
    {
        foreach ($typeMappings as $mapping) {
            $this->register($mapping);
        }
    }

    /**
     * @return TypeRegistry
     */
    public static function withDefaultTypes(): TypeRegistry
    {
        $registry = new static();
        $registry->register(new TextMapping());

        return $registry;
    }

    /**
     * @param TypeMapping $mapping
     */
    public function register(TypeMapping $mapping)
    {
        $this->mapping[] = $mapping;
    }

    /**
     * @param Table           $table
     * @param string          $name
     * @param FieldDefinition $definition
     *
     * @throws DoctrineStorageException
     */
    public function map(Table $table, string $name, FieldDefinition $definition)
    {
        foreach ($this->mapping as $mapping) {
            if ($mapping->maps($definition->type())) {
                $mapping->map($table, $name, $definition);
                return;
            }
        }

        throw DoctrineStorageException::unableToMapType($definition->type());
    }
}
