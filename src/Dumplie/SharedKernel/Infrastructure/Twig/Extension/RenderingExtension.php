<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\Twig\Extension;

use Dumplie\SharedKernel\Application\View\ContextMap;
use Dumplie\SharedKernel\Application\View\ViewObject;
use Dumplie\SharedKernel\Infrastructure\Twig\TwigRenderingEngine;

final class RenderingExtension extends \Twig_Extension
{
    /**
     * @var ContextMap
     */
    private $contextMap;

    /**
     * @param ContextMap $contextMap
     */
    public function __construct(ContextMap $contextMap)
    {
        $this->contextMap = $contextMap;
    }

    public function getName()
    {
        return 'dumplie_rendering_extension';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('dumplie_render', [$this, 'render'], ['needs_environment' => true, 'is_safe' => ['html']]),
        );
    }

    /**
     * @param \Twig_Environment $env
     * @param ViewObject $object
     * @param string $extension
     * @return array
     */
    public function render(\Twig_Environment $env, ViewObject $object, string $context = null, string $extension = 'html')
    {
        return (new TwigRenderingEngine($env, $this->contextMap))->render($object, $context, $extension);
    }
}