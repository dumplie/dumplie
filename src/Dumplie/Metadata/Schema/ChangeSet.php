<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Schema;

final class ChangeSet
{
    /**
     * @var string
     */
    private $operations;

    /**
     * @param array $operations
     */
    public function __construct(array $operations = [])
    {
        $this->operations = $operations;
    }

    /**
     * @return array
     */
    public function operations() : array
    {
        return $this->operations;
    }
}