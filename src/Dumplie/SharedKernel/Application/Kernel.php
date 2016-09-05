<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application;

use Dumplie\SharedKernel\Application\Exception\KernelException;

final class Kernel
{
    /**
     * @var Extension[]
     */
    private $extensions;

    public function __construct()
    {
        $this->extensions = [];
    }

    /**
     * @param Extension $extension
     */
    public function register(Extension $extension)
    {
        $this->extensions[get_class($extension)] = $extension;
    }

    /**
     * @param ServiceContainer $container
     * @throws KernelException
     */
    public function build(ServiceContainer $container)
    {
        foreach ($this->extensions as $extension) {
            foreach ($extension->dependsOn() as $expectedExtensionClass) {
                if (!array_key_exists($expectedExtensionClass, $this->extensions)) {
                    throw KernelException::missingExtension($expectedExtensionClass);
                }
            }
        }

        foreach ($this->extensions as $extension) {
            $extension->build($container);
        }
    }

    /**
     * @param ServiceLocator $locator
     * @throws KernelException
     */
    public function boot(ServiceLocator $locator)
    {
        foreach ($this->extensions as $extension) {
            $extension->boot($locator);
        }
    }
}