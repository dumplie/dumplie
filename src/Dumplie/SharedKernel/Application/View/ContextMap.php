<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\View;

final class ContextMap
{
    /**
     * @var array
     */
    private $extendedContexts;

    /**
     * @var array
     */
    private $viewObjectsContexts;

    public function __construct()
    {
        $this->extendedContexts = [];
        $this->viewObjectsContexts = [];
    }

    /**
     * @param string $context
     * @param string $extendedContext
     */
    public function extendContext(string $context, string $extendedContext)
    {
        if (array_key_exists($context, $this->extendedContexts)) {
            throw new \RuntimeException(sprintf("Context \"%s\" is already extended by, \"%s\"", $context, $this->extendedContexts[$context]));
        }

        $this->extendedContexts[$context] = $extendedContext;
    }

    /**
     * @param string $context
     * @return bool
     */
    public function isExtended(string $context) : bool
    {
        return array_key_exists($context, $this->extendedContexts);
    }

    /**
     * @param string $context
     * @return string
     */
    public function getExtendedContext(string $context) : string
    {
        return $this->extendedContexts[$context];
    }

    /**
     * @param ViewObject $viewObject
     * @return bool
     */
    public function hasContextFor(ViewObject $viewObject) : bool
    {
        return array_key_exists(get_class($viewObject), $this->viewObjectsContexts);
    }

    /**
     * @param string $viewObjectClass
     * @param string $context
     */
    public function setContext(string $viewObjectClass, string $context)
    {
        $this->viewObjectsContexts[$viewObjectClass] = $context;
    }

    /**
     * @param ViewObject $viewObject
     * @return string
     */
    public function getContextFor(ViewObject $viewObject) : string
    {
        return $this->viewObjectsContexts[get_class($viewObject)];
    }
}