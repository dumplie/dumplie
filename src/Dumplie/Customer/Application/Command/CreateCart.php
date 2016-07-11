<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Command;

use Dumplie\SharedKernel\Application\Command\Command;
use Dumplie\SharedKernel\Application\Command\CommandSerialize;

final class CreateCart implements Command
{
    use CommandSerialize;

    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $currency;

    /**
     * @param string $uuid
     * @param string $currency
     */
    public function __construct(string $uuid, string $currency)
    {
        $this->currency = $currency;
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function currency() : string
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function uuid() : string
    {
        return $this->uuid;
    }
}
