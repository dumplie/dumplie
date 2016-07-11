<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Tests\Integration\Application\InMemory;

use Dumplie\Customer\Application\Command\AddToCart;
use Dumplie\Customer\Domain\CartId;

class CommandTest extends \PHPUnit_Framework_TestCase
{
    public function test_compound_command_serialization()
    {
        $compoundCommand = new AddToCart("SKU_1", 5, (string) CartId::generate());

        $serialized = serialize($compoundCommand);

        $unserialized = unserialize($serialized);

        $this->assertEquals("SKU_1", $unserialized->sku());
        $this->assertEquals(5, $unserialized->quantity());
    }
}