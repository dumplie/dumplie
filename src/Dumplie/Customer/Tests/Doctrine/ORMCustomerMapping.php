<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Tests\Doctrine;

use Dumplie\Customer\Infrastructure\Doctrine\ORM\Type\Domain\CartItemsType;
use Dumplie\Customer\Infrastructure\Doctrine\ORM\Type\Domain\OrderItemsType;
use Dumplie\SharedKernel\Tests\Doctrine\EntityManagerBuilder;

trait ORMCustomerMapping
{
    /**
     * @param EntityManagerBuilder $builder
     */
    public function registerCustomerMapping(EntityManagerBuilder $builder)
    {
        $builder->registerMapping(DUMPLIE_SRC_PATH . '/Dumplie/Customer/Infrastructure/Doctrine/ORM/Resources/Mapping/Domain', 'Dumplie\Customer\Domain')
            ->registerCustomType(CartItemsType::NAME, CartItemsType::class)
            ->registerCustomType(OrderItemsType::NAME, OrderItemsType::class);
    }
}