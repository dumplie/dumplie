<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application;

use Dumplie\SharedKernel\Application\ServiceContainer\Definition;

interface ServiceContainer
{
    /**
     * @param $id
     * @param Definition $definition
     */
    public function register(string $id, Definition $definition);

    /**
     * @param string $id
     * @return mixed
     */
    public function definitionExists(string $id) : bool;

    /**
     * @param string $id
     * @return string
     */
    public function definitionClass(string $id) : string;
}