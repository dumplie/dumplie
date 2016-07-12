<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Application\Schema;

use Dumplie\Metadata\Application\Exception\InvalidTypeException;

final class Type
{
    const TYPE_ASSOCIATION = 'association';
    const TYPE_TEXT = 'text';

    private $allowed = [
        self::TYPE_ASSOCIATION,
        self::TYPE_TEXT
    ];

    private $type;

    /**
     * Type constructor.
     *
     * @param string $type
     *
     * @throws InvalidTypeException
     */
    public function __construct(string $type)
    {
        if (!in_array($type, $this->allowed)) {
            throw InvalidTypeException::invalidType($type, $this->allowed);
        }

        $this->type = $type;
    }

    /**
     * @return Type
     */
    public static function association(): Type
    {
        return new static(static::TYPE_ASSOCIATION);
    }

    /**
     * @return Type
     */
    public static function text(): Type
    {
        return new static(static::TYPE_TEXT);
    }

    /**
     * @param Type $type
     *
     * @return bool
     */
    public function isEqual(Type $type): bool
    {
        return $this->type == $type->type;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->type;
    }
}
