<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\Twig;

use Dumplie\SharedKernel\Application\Extension;
use Dumplie\SharedKernel\Application\ServiceContainer;
use Dumplie\SharedKernel\Application\ServiceLocator;
use Dumplie\SharedKernel\Application\Services;
use Dumplie\SharedKernel\Application\View\ContextMap;

final class TwigExtension implements Extension
{
    /**
     * @var string
     */
    private $twigEnvServiceId;

    /**
     * @param string $twigEnvServiceId
     */
    public function __construct(string $twigEnvServiceId)
    {
        $this->twigEnvServiceId = $twigEnvServiceId;
    }

    /**
     * @return array
     */
    public function dependsOn() : array
    {
        return [];
    }

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function build(ServiceContainer $serviceContainer)
    {
        $serviceContainer->register(
            Services::KERNEL_RENDERING_CONTEXT_MAP,
            new ServiceContainer\Definition(
                ContextMap::class
            )
        );

        $serviceContainer->register(
            Services::KERNEL_RENDERING_ENGINE,
            new ServiceContainer\Definition(
                TwigRenderingEngine::class,
                [
                    new ServiceContainer\ArgumentService($this->twigEnvServiceId),
                    new ServiceContainer\ArgumentService(Services::KERNEL_RENDERING_CONTEXT_MAP)
                ]
            )
        );
    }

    /**
     * @param ServiceLocator $serviceLocator
     */
    public function boot(ServiceLocator $serviceLocator)
    {
    }
}