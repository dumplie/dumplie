<?php

namespace Spec\Dumplie\SharedKernel\Domain\Product;

use Dumplie\SharedKernel\Domain\Exception\InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SKUSpec extends ObjectBehavior
{
    function it_cant_be_empty()
    {
        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', ['']);
    }
}
