<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Domain\Event;

use Dumplie\SharedKernel\Domain\Event\Event;

final class CustomerPlacedOrder implements Event
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->date = new \DateTimeImmutable();
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function orderId() : string
    {
        return $this->id;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function date() : \DateTimeImmutable
    {
        return $this->date;
    }
}