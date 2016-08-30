<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Schema\Field;

use Dumplie\Metadata\Schema\FieldDefinition;
use Dumplie\Metadata\Schema\Type;

final class BoolField implements FieldDefinition
{
    const NAME = 'bool';

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
     * @param bool|null $default
     * @param bool        $nullable
     * @param array       $options
     */
    public function __construct(bool $default = null, bool $nullable = true, array $options = [])
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
        return Type::bool();
    }

    /**
     * @return array
     */
    public function options() : array
    {
        return $this->options;
    }

    /**
     * @return bool|null
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
     * @return bool
     */
    public function isValid($value) : bool
    {
        return is_bool($value);
    }

    /**
     * @param $value
     * @return string
     */
    public function serialize($value) : string
    {
        return ((bool) $value) ? "1" : "0";
    }

    /**
     * @param $serializedValue
     * @return bool
     */
    public function deserialize($serializedValue)
    {
        return (bool) $serializedValue;
    }
}