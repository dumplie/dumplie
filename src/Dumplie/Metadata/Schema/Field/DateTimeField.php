<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Schema\Field;

use Dumplie\Metadata\Exception\InvalidArgumentException;
use Dumplie\Metadata\Exception\InvalidValueException;
use Dumplie\Metadata\Schema\FieldDefinition;
use Dumplie\Metadata\Schema\Type;

final class DateTimeField implements FieldDefinition
{
    const NAME = 'datetime';

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
     * @var string
     */
    private $format;

    /**
     * @param \DateTimeInterface|null $default
     * @param bool                    $nullable
     * @param array                   $options
     * @param string                  $format
     */
    public function __construct(\DateTimeInterface $default = null, bool $nullable = true, array $options = [], string $format = 'c')
    {
        $this->nullable = $nullable;
        $this->default = $default;
        $this->options = $options;
        $this->format = $format;
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
        return Type::dateTime();
    }

    /**
     * @return array
     */
    public function options() : array
    {
        return $this->options;
    }

    /**
     * @return \DateTimeInterface|null
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

        return $value instanceof \DateTimeImmutable;
    }

    /**
     * @param $value
     *
     * @return string
     * @throws InvalidValueException
     */
    public function serialize($value) : string
    {
        if (!$value instanceof \DateTimeInterface) {
            throw InvalidValueException::valueDoesNotMatchType($this, $value);
        }

        return (string) $value->format($this->format);
    }

    /**
     * @param $serializedValue
     *
     * @return \DateTimeInterface
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

        return new \DateTimeImmutable($serializedValue);
    }
}
