<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Inventory;

use Dumplie\Application\Command\Command;
use Dumplie\Application\Command\CommandSerialize;

final class PutBackProductToStock implements Command
{
    use CommandSerialize;
    
    /**
     * @var string
     */
    private $sku;

    /**
     * PutBackProductToStock constructor.
     *
     * @param string $sku
     */
    public function __construct(string $sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function sku(): string
    {
        return $this->sku;
    }
}
