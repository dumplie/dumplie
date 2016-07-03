<?php

declare (strict_types = 1);

namespace Dumplie\Test\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Dumplie\Infrastructure\Doctrine\ORM\Type\Domain\Customer\CartItemsType;

class ORMTestCase extends DbalTestCase
{
    /**
     * @return EntityManager
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    protected function createEntityManager()
    {
        $namespaces = [
            DUMPLIE_SRC_PATH . '/Dumplie/Infrastructure/Doctrine/ORM/Resources/Mapping/Domain/Inventory' => 'Dumplie\Domain\Inventory',
            DUMPLIE_SRC_PATH . '/Dumplie/Infrastructure/Doctrine/ORM/Resources/Mapping/Domain/Customer' => 'Dumplie\Domain\Customer',
            DUMPLIE_SRC_PATH . '/Dumplie/Infrastructure/Doctrine/ORM/Resources/Mapping/Domain/SharedKernel' => 'Dumplie\Domain\SharedKernel',
        ];

        $dbParams = json_decode(DUMPLIE_TEST_DB_CONNECTION, true);

        $driver = new SimplifiedXmlDriver($namespaces);
        $config = Setup::createConfiguration(true);

        $config->setMetadataDriverImpl($driver);

        if (!Type::hasType(CartItemsType::NAME)) {
            Type::addType(CartItemsType::NAME, CartItemsType::class);
        }

        $em = EntityManager::create($dbParams, $config);
        $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping(CartItemsType::NAME, CartItemsType::NAME);

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