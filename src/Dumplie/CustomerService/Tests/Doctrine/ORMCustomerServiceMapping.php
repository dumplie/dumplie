<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Tests\Doctrine;

use Dumplie\SharedKernel\Tests\Doctrine\EntityManagerBuilder;

trait ORMCustomerServiceMapping
{
    /**
     * @param EntityManagerBuilder $builder
     */
    public function registerCustomerServiceMapping(EntityManagerBuilder $builder)
    {
        $builder->registerMapping(DUMPLIE_SRC_PATH . '/Dumplie/CustomerService/Infrastructure/Doctrine/ORM/Resources/Mapping/Domain', 'Dumplie\CustomerService\Domain');
    }
}