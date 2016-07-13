<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Schema\Field;

use Dumplie\Metadata\Exception\InvalidArgumentException;
use Dumplie\Metadata\Exception\InvalidValueException;
use Dumplie\Metadata\Schema\FieldDefinition;
use Dumplie\Metadata\Schema\Type;

final class TextField implements FieldDefinition
{
    const NAME = 'text';

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
     * @param string|null $default
     * @param bool        $nullable
     * @param array       $options
     */
    public function __construct(string $default = null, bool $nullable = false, array $options = [])
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
        return Type::text();
    }

    /**
     * @return array
     */
    public function options() : array
    {
        return $this->options;
    }

    /**
     * @return null|string
     */
    public function defaultValue()
    {
        return $this->default;
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
     * @return bool
     */
    public function isValid($value) : bool
    {
        if ($value === null && $this->nullable) {
            return true;
        }

        return is_string($value);
    }

    /**
     * @param $value
     * @return string
     * @throws InvalidValueException
     */
    public function serialize($value) : string
    {
        if (!is_string($value)) {
            throw InvalidValueException::valueDoesNotMatchType($this, $value);
        }

        return $value;
    }

    /**
     * @param string $serializedValue
     * @return string
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

        return $serializedValue;
    }
}
