<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\Twig;

use Dumplie\SharedKernel\Application\RenderingEngine;
use Dumplie\SharedKernel\Application\View\ContextMap;
use Dumplie\SharedKernel\Application\View\ViewObject;

final class TwigRenderingEngine implements RenderingEngine
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var ContextMap
     */
    private $contextMap;

    /**
     * @param \Twig_Environment $twig
     * @param ContextMap $contextMap
     */
    public function __construct(\Twig_Environment $twig, ContextMap $contextMap)
    {
        $this->twig = $twig;
        $this->contextMap = $contextMap;
    }

    /**
     * @param ViewObject $viewObject
     * @param string $context
     * @param string $format
     * @return string
     */
    public function render(ViewObject $viewObject, string $context = null, string $format = 'html') : string
    {
        $viewContext = (is_null($context)) ? $viewObject->context() : $context;

        $templateName = sprintf("@%s/%s.%s.twig", $viewContext, $viewObject->name(), $format);

        $extendedTemplateName = $this->contextMap->isExtended($viewContext)
            ? sprintf("@%s/%s.%s.twig", $this->contextMap->getExtendedContext($viewContext), $viewObject->name(), $format)
            : $templateName;

        if ($this->contextMap->hasContextFor($viewObject)) {
            $extendedTemplateName = $templateName = sprintf("@%s/%s.%s.twig", $this->contextMap->getContextFor($viewObject), $viewObject->name(), $format);;
        }

        try {
            $template = $this->twig->loadTemplate($extendedTemplateName);
        } catch (\Twig_Error_Loader $e) {
            $template = $this->twig->loadTemplate($templateName);
        }

        return $template->render(['object' => $viewObject]);
    }
}