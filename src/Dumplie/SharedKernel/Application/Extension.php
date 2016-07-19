<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application;

interface Extension
{
    /**
     * Used to register services in ServiceContainer
     *
     * @param ServiceContainer $serviceContainer
     */
    public function configure(ServiceContainer $serviceContainer);

    /**
     * Executed immediately after initialization of ServiceLocator
     * it's the first place where CommandExtensions can be registered.
     *
     * @param ServiceLocator $serviceLocator
     */
    public function boot(ServiceLocator $serviceLocator);
}