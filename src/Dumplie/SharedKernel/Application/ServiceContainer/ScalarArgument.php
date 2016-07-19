<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\ServiceContainer;

final class ScalarArgument implements Argument
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }
}