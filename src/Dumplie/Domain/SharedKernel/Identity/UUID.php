<?php

declare (strict_types = 1);

namespace Dumplie\Domain\SharedKernel\Identity;

use Dumplie\Domain\SharedKernel\Exception\InvalidUUIDFormatException;
use Ramsey\Uuid\Uuid as BaseUUID;

class UUID
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     *
     * @throws InvalidUUIDFormatException
     */
    public function __construct(string $value)
    {
        $pattern = '/'.BaseUUID::VALID_PATTERN.'/';

        if (!preg_match($pattern, (string) $value)) {
            throw new InvalidUUIDFormatException();
        }

        $this->value = (string) $value;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return (string) $this->value;
    }

    /**
     * @param UUID $id
     *
     * @return bool
     */
    public function isEqual(UUID $id) : bool
    {
        return $this->value === $id->value;
    }
}
