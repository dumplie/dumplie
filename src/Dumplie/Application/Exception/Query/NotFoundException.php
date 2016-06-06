<?php

declare (strict_types = 1);

namespace Dumplie\Application\Exception\Query;

class NotFoundException extends Exception
{
    /**
     * @param string $id
     *
     * @return NotFoundException
     */
    public static function cartWithId(string $id) : NotFoundException
    {
        return new self(sprintf('Cart with id "%s" was not found.', $id));
    }
}
