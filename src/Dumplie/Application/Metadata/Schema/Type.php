<?php

declare (strict_types = 1);

namespace Dumplie\Application\Metadata\Schema;

use Dumplie\Application\Exception\Metadata\InvalidTypeException;

final class Type
{
    const TYPE_TEXT = 'text';

    private $allowed = [
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
