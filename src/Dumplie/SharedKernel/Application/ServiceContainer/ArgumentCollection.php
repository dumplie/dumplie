<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\ServiceContainer;

final class ArgumentCollection implements Argument
{
    /**
     * @var array
     */
    private $arguments;

    /**
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->arguments = [];
        foreach ($arguments as $argument) {
            $this->addArgument($argument);
        }
    }

    /**
     * @return array
     */
    public function value()
    {
        return $this->arguments;
    }

    /**
     * @param Argument $argument
     */
    private function addArgument(Argument $argument)
    {
        $this->arguments[] = $argument;
    }
}