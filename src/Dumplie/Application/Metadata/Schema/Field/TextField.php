<?php

declare (strict_types = 1);

namespace Dumplie\Application\Metadata\Schema\Field;

use Dumplie\Application\Exception\Metadata\InvalidArgumentException;
use Dumplie\Application\Exception\Metadata\InvalidValueException;
use Dumplie\Application\Metadata\Schema\FieldDefinition;

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
     * @param string|null $default
     * @param bool $nullable
     */
    public function __construct(string $default = null, bool $nullable = false)
    {
        $this->nullable = $nullable;
        $this->default = $default;
    }

    /**
     * @return string
     */
    public function name() : string
    {
        return self::NAME;
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