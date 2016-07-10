<?php

declare (strict_types = 1);

namespace Dumplie\Test\Doctrine;

use Doctrine\DBAL\Logging\SQLLogger;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Dumplie\Infrastructure\Doctrine\ORM\Type\Domain\Customer\CartItemsType;
use Dumplie\Infrastructure\Doctrine\ORM\Type\Domain\Customer\OrderItemsType;
use Dumplie\Infrastructure\Doctrine\ORM\Type\Domain\CustomerService\OrderStateType;
use Dumplie\Infrastructure\Doctrine\ORM\Type\Domain\CustomerService\PaymentStateType;

trait ORMHelper
{
    use DBALHelper;

    /**
     * @return EntityManager
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    protected function createEntityManager(SQLLogger $logger = null)
    {
        $namespaces = [
            DUMPLIE_SRC_PATH . '/Dumplie/Infrastructure/Doctrine/ORM/Resources/Mapping/Domain/Inventory' => 'Dumplie\Domain\Inventory',
            DUMPLIE_SRC_PATH . '/Dumplie/Infrastructure/Doctrine/ORM/Resources/Mapping/Domain/Customer' => 'Dumplie\Domain\Customer',
            DUMPLIE_SRC_PATH . '/Dumplie/Infrastructure/Doctrine/ORM/Resources/Mapping/Domain/CustomerService' => 'Dumplie\Domain\CustomerService',
            DUMPLIE_SRC_PATH . '/Dumplie/Infrastructure/Doctrine/ORM/Resources/Mapping/Domain/SharedKernel' => 'Dumplie\Domain\SharedKernel',
        ];

        $dbParams = json_decode(DUMPLIE_TEST_DB_CONNECTION, true);

        $driver = new SimplifiedXmlDriver($namespaces);
        $config = Setup::createConfiguration(true);

        $config->setMetadataDriverImpl($driver);

        if (!is_null($logger)) {
            $config->setSQLLogger($logger);
        }

        if (!Type::hasType(CartItemsType::NAME)) {
            Type::addType(CartItemsType::NAME, CartItemsType::class);
        }
        if (!Type::hasType(OrderItemsType::NAME)) {
            Type::addType(OrderItemsType::NAME, OrderItemsType::class);
        }
        if (!Type::hasType(OrderStateType::NAME)) {
            Type::addType(OrderStateType::NAME, OrderStateType::class);
        }
        if (!Type::hasType(PaymentStateType::NAME)) {
            Type::addType(PaymentStateType::NAME, PaymentStateType::class);
        }

        $em = EntityManager::create($dbParams, $config);
        $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping(CartItemsType::NAME, CartItemsType::NAME);
        $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping(OrderItemsType::NAME, OrderItemsType::NAME);
        $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping(OrderStateType::NAME, OrderStateType::NAME);
        $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping(PaymentStateType::NAME, PaymentStateType::NAME);

        return $em;
    }

    /**
     * @param EntityManager $entityManager
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    protected function createSchema(EntityManager $entityManager)
    {
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        $customerTool = new SchemaTool($entityManager);
        $customerTool->dropSchema($metadata);
        $customerTool->createSchema($metadata);
    }

    /**
     * @param EntityManager $entityManager
     */
    protected function dropSchema(EntityManager $entityManager)
    {
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $customerTool = new SchemaTool($entityManager);
        $customerTool->dropSchema($metadata);
    }
}