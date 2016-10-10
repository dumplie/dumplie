<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Schema\Field;

use Dumplie\Metadata\Exception\InvalidArgumentException;
use Dumplie\Metadata\Exception\InvalidValueException;
use Dumplie\Metadata\Schema\FieldDefinition;
use Dumplie\Metadata\Schema\Type;

final class FloatField implements FieldDefinition
{
    const NAME = 'float';

    /**
     * @var null
     */
    private $default;

    /**
     * @var bool
     */
    private $nullable;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @param float|null $default
     * @param bool       $nullable
     * @param array      $options
     */
    public function __construct(float $default = null, bool $nullable = true, array $options = [])
    {
        $this->nullable = $nullable;
        $this->default = $default;
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
        return Type::float();
    }

    /**
     * @return array
     */
    public function options() : array
    {
        return $this->options;
    }

    /**
     * @return float|null
     */
    public function defaultValue()
    {
        return $this->default;
    }

    /**
     * @return bool
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
        return is_float($value);
    }

    /**
     * @param $value
     *
     * @return string
     * @throws InvalidValueException
     */
    public function serialize($value) : string
    {
        if (!is_float($value)) {
            throw InvalidValueException::valueDoesNotMatchType($this, $value);
        }

        return (string) $value;
    }

    /**
     * @param $serializedValue
     *
     * @return float
     * @throws InvalidArgumentException
     */
    public function deserialize($serializedValue)
    {
        if (null === $serializedValue && $this->isNullable()) {
            return ($this->default === null) ? null : $this->default;
        }

        if (!is_string($serializedValue)) {
            throw InvalidArgumentException::expected('string', $serializedValue);
        }

        return (float) $serializedValue;
    }
}
