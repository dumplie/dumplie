<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Tests\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Logging\SQLLogger;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use Doctrine\ORM\Tools\Setup;

final class EntityManagerBuilder
{
    /**
     * @var array
     */
    private $mappings;

    /**
     * @var SQLLogger
     */
    private $logger;

    /**
     * @var array
     */
    private $customTypes;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->mappings = [
            DUMPLIE_SRC_PATH . '/Dumplie/SharedKernel/Infrastructure/Doctrine/ORM/Resources/Mapping/Domain' => 'Dumplie\SharedKernel\Domain'
        ];
        $this->customTypes = [];
        $this->connection = $connection;
    }

    /**
     * @param $path
     * @param $namespace
     * @return EntityManagerBuilder
     */
    public function registerMapping(string $path, string $namespace)
    {
        $this->mappings[$path] = $namespace;

        return $this;
    }

    /**
     * @param SQLLogger $logger
     * @return EntityManagerBuilder
     */
    public function registerLogger(SQLLogger $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param string $typeName
     * @param string $className
     * @return EntityManagerBuilder
     */
    public function registerCustomType(string $typeName, string $className)
    {
        $this->customTypes[$typeName] = $className;

        return $this;
    }

    /**
     * @return EntityManager
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    public function build() : EntityManager
    {
        $driver = new SimplifiedXmlDriver($this->mappings);
        $config = Setup::createConfiguration(true);

        $config->setMetadataDriverImpl($driver);

        if (!is_null($this->logger)) {
            $config->setSQLLogger($this->logger);
        }

        foreach ($this->customTypes as $customTypeName => $customTypeClass) {
            if (!Type::hasType($customTypeName)) {
                Type::addType($customTypeName, $customTypeClass);
            }
        }

        $em = EntityManager::create($this->connection, $config);

        foreach ($this->customTypes as $customTypeName => $customTypeClass) {
            $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping($customTypeName, $customTypeName);
        }

        return $em;
    }
}