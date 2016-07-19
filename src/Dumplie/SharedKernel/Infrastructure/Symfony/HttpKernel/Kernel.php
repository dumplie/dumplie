<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\Symfony\HttpKernel;

use Dumplie\SharedKernel\Infrastructure\Symfony\DependencyInjeciton\ServiceContainer;
use Dumplie\SharedKernel\Infrastructure\Symfony\DependencyInjeciton\ServiceLocator;
use ReflectionClass;
use Symfony\Component\HttpKernel\Kernel as HttpKernel;

abstract class Kernel extends HttpKernel
{
    /**
     * Returns and array of extensions to register.
     *
     * @return array
     */
    abstract protected function registerDumplieExtensions();

    protected function buildContainer()
    {
        $builder = parent::buildContainer();

        $serviceContainer = new ServiceContainer($builder);

        foreach ($this->registerDumplieExtensions() as $extension) {
            $extension->configure($serviceContainer);
        }

        return $builder;
    }

    final public function boot()
    {
        if (true === $this->booted) {
            return;
        }

        if ($this->loadClassCache) {
            $this->doLoadClassCache($this->loadClassCache[0], $this->loadClassCache[1]);
        }

        // init bundles
        $this->initializeBundles();

        // init container
        $this->initializeContainer();

        foreach ($this->getBundles() as $bundle) {
            $bundle->setContainer($this->container);
            $bundle->boot();
        }

        foreach ($this->registerDumplieExtensions() as $extension) {
            $extension->boot($this->container);
        }

        $this->booted = true;
    }

    final protected function getContainerBaseClass()
    {
        return ServiceLocator::class;
    }

    final protected function getKernelParameters()
    {
        $reflection = new ReflectionClass(\Dumplie\SharedKernel\Application\ServiceLocator::class);
        $dumplieRootDir = realpath(dirname($reflection->getFileName()) . '/../../');

        if (!file_exists($dumplieRootDir)) {
            throw new \RuntimeException(sprintf("Dumplie root dir path \"%s\"does not exists.", $dumplieRootDir));
        }

        return array_merge(
            [
                'dumplie.root_dir' => $dumplieRootDir
            ],
            parent::getKernelParameters()
        );
    }
}