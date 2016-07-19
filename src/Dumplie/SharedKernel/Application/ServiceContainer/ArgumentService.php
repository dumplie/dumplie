<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\ServiceContainer;

final class ArgumentService implements Argument
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->id;
    }
}