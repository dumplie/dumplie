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

    /**
     * @var bool
     */
    private $built;

    /**
     * @var bool
     */
    private $booted;

    public function __construct()
    {
        $this->extensions = [];
        $this->built = false;
        $this->booted = false;
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

        $this->built = true;
    }

    /**
     * @param ServiceLocator $locator
     * @throws KernelException
     */
    public function boot(ServiceLocator $locator)
    {
        if (!$this->built) {
            throw KernelException::notBuilt();
        }

        foreach ($this->extensions as $extension) {
            $extension->boot($locator);
        }

        $this->booted = true;
    }
}