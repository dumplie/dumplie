<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Schema\Field;

use Dumplie\Metadata\Association;
use Dumplie\Metadata\Metadata;
use Dumplie\Metadata\Schema\AssociationFieldDefinition;
use Dumplie\Metadata\Schema\Type;
use Dumplie\Metadata\Schema\TypeSchema;
use Dumplie\Metadata\Exception\InvalidArgumentException;
use Dumplie\Metadata\Exception\InvalidValueException;

final class AssociationField implements AssociationFieldDefinition
{
    const NAME = 'association';

    /**
     * @var string
     */
    private $schema;

    /**
     * @var TypeSchema
     */
    private $typeSchema;

    /**
     * @var bool
     */
    private $nullable;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @param string     $schema
     * @param TypeSchema $typeSchema
     * @param bool       $nullable
     * @param array      $options
     */
    public function __construct(string $schema, TypeSchema $typeSchema, bool $nullable = false, array $options = [])
    {
        $this->schema = $schema;
        $this->typeSchema = $typeSchema;
        $this->nullable = $nullable;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function name() : string
    {
        return self::NAME;
    }

    /**
     * @return Type
     */
    public function type() : Type
    {
        return Type::association();
    }

    /**
     * @return string
     */
    public function schema() : string
    {
        return $this->schema;
    }

    /**
     * @return TypeSchema
     */
    public function typeSchema() : TypeSchema
    {
        return $this->typeSchema;
    }

    /**
     * @return array
     */
    public function options() : array
    {
        return $this->options;
    }

    /**
     * @return null
     */
    public function defaultValue()
    {
        return;
    }

    /**
     * @return boolean
     */
    public function isNullable() : bool
    {
        return $this->nullable;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isValid($value) : bool
    {
        if ($value === null && $this->nullable) {
            return true;
        }

        return $value instanceof Metadata;
    }

    /**
     * @param $value
     *
     * @return string
     * @throws InvalidValueException
     */
    public function serialize($value) : string
    {
        if (!$value instanceof Metadata) {
            throw InvalidValueException::valueDoesNotMatchType($this, $value);
        }

        return (string) $value->id();
    }

    /**
     * @param string $serializedValue
     *
     * @return null|Association
     * @throws InvalidArgumentException
     */
    public function deserialize($serializedValue)
    {
        if (null === $serializedValue && $this->isNullable()) {
            return null;
        }

        if (!is_string($serializedValue)) {
            throw InvalidArgumentException::expected('string', $serializedValue);
        }

        return new Association($this->schema, $this->typeSchema, ['id' => $serializedValue]);
    }
}
