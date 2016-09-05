<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\Twig;

use Dumplie\Inventory\UserInterface\Views as InventoryViews;

class DumplieFileLoader extends \Twig_Loader_Filesystem
{
    /**
     * @param array $paths
     */
    public function __construct(array $paths = [])
    {
        parent::__construct($paths);

        $this->registerDumplieContexts();
    }

    public function registerDumplieContexts()
    {
        $this->addPath(InventoryViews::directory() . '/Twig', InventoryViews::CONTEXT);
    }

    /**
     * @param string $path
     * @param string $context
     */
    public function addContextPath(string $path, string $context)
    {
        $this->addPath($path, $context);
    }
}