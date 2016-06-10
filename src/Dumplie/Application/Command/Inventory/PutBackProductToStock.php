<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Inventory;

final class PutBackProductToStock
{
    /**
     * @var string
     */
    private $sku;

    public function __construct(string $sku)
    {
        $this->sku = $sku;
    }

    public function sku(): string
    {
        return $this->sku;
    }
}
