<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\UserInterface;

final class Views
{
    const CONTEXT = 'dumplie_core_inventory';

    /**
     * @return string
     */
    static public function directory() : string
    {
        return __DIR__ . '/Resources';
    }
}