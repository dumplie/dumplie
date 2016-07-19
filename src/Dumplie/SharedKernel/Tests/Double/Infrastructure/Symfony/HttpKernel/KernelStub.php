<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Tests\Double\Infrastructure\Symfony\HttpKernel;

use Symfony\Component\Config\Loader\LoaderInterface;

final class KernelStub extends \Dumplie\SharedKernel\Infrastructure\Symfony\HttpKernel\Kernel
{
    /**
     * @var array
     */
    private $extensions;

    /**
     * @param array $extension
     */
    public function __construct(array $extension = [])
    {
        parent::__construct('test', false);
        $this->extensions = $extension;
    }

    /**
     * @param array $extensios
     */
    public function setExtensions(array $extensios = [])
    {
        $this->extensions = $extensios;
    }

    /**
     * @return array
     */
    public function registerBundles()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function registerDumplieExtensions()
    {
        return $this->extensions;
    }

    public function getCacheDir()
    {
        return sys_get_temp_dir() . '/dumplie/symfony/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return sys_get_temp_dir() . '/dumplie/symfony/var/logs';
    }

    protected function getContainerClass()
    {
        return $this->name . md5(microtime()) . 'DumplieTestContainer';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }
}