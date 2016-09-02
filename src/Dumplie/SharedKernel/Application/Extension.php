<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application;

interface Extension
{
    /**
     * List of class names of extensions that this one depends on.
     *
     * [
     *     \Dumplie\SharedKernel\Application\Extension\CoreExtension::class
     * ]
     *
     * @return array
     */
    public function dependsOn() : array;

    /**
     * Used to register services in ServiceContainer
     *
     * @param ServiceContainer $serviceContainer
     */
    public function build(ServiceContainer $serviceContainer);

    /**
     * Executed immediately after initialization of ServiceLocator
     * it's the first place where CommandExtensions can be registered.
     *
     * @param ServiceLocator $serviceLocator
     */
    public function boot(ServiceLocator $serviceLocator);
}