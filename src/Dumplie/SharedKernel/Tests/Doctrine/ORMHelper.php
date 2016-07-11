<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Tests\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

trait ORMHelper
{
    use DBALHelper;

    /**
     * @return EntityManagerBuilder
     */
    protected function entityManagerBuilder() : EntityManagerBuilder
    {
        return new EntityManagerBuilder(static::createConnection());
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