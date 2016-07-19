<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\ServiceContainer;

final class Definition
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var Argument[]
     */
    private $arguments;

    /**
     * @param string $className
     * @param array $arguments
     */
    public function __construct(string $className, array $arguments = [])
    {
        $this->arguments = [];

        foreach ($arguments as $argument) {
            $this->addArgument($argument);
        }
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function className()
    {
        return $this->className;
    }

    /**
     * @param Argument $argument
     */
    public function addArgument(Argument $argument)
    {
        $this->arguments[] = $argument;
    }

    /**
     * @return Argument[]
     */
    public function arguments() : array
    {
        return $this->arguments;
    }

    /**
     * @return bool
     */
    public function hasArguments() : bool
    {
        return (bool) count($this->arguments());
    }
}