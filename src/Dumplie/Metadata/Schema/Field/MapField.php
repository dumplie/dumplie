<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Schema\Field;

use Dumplie\Metadata\Exception\InvalidArgumentException;
use Dumplie\Metadata\Exception\InvalidValueException;
use Dumplie\Metadata\Schema\FieldDefinition;
use Dumplie\Metadata\Schema\Type;

final class MapField implements FieldDefinition
{
    const NAME = 'map';

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
     * @param array|null $default
     * @param bool       $nullable
     * @param array      $options
     */
    public function __construct(array $default = null, bool $nullable = true, array $options = [])
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
        return Type::map();
    }

    /**
     * @return array
     */
    public function options() : array
    {
        return $this->options;
    }

    /**
     * @return array|null
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
        if ($value === null && $this->nullable) {
            return true;
        }

        return is_array($value);
    }

    /**
     * @param $value
     *
     * @return string
     * @throws InvalidValueException
     */
    public function serialize($value) : string
    {
        if (null !== $value && !is_array($value)) {
            throw InvalidValueException::valueDoesNotMatchType($this, $value);
        }

        return json_encode($value);
    }

    /**
     * @param $serializedValue
     *
     * @return array
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

        return json_decode($serializedValue, true);
    }
}
