<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\Tests\Doctrine;

use Dumplie\CustomerService\Infrastructure\Doctrine\ORM\Type\Domain\OrderStateType;
use Dumplie\CustomerService\Infrastructure\Doctrine\ORM\Type\Domain\PaymentStateType;
use Dumplie\SharedKernel\Tests\Doctrine\EntityManagerBuilder;

trait ORMInventoryMapping
{
    /**
     * @param EntityManagerBuilder $builder
     */
    public function registerInventoryMapping(EntityManagerBuilder $builder)
    {
        $builder->registerMapping(DUMPLIE_SRC_PATH . '/Dumplie/Inventory/Infrastructure/Doctrine/ORM/Resources/Mapping/Domain', 'Dumplie\Inventory\Domain')
            ->registerCustomType(OrderStateType::NAME, OrderStateType::class)
            ->registerCustomType(PaymentStateType::NAME, PaymentStateType::class);
    }
}