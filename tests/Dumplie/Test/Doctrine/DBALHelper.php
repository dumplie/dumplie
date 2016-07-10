<?php

declare (strict_types = 1);

namespace Dumplie\Test\Doctrine;

use Doctrine\DBAL\DriverManager;

trait DBALHelper
{
    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    protected static function createDatabase()
    {
        $dbParams = json_decode(DUMPLIE_TEST_DB_CONNECTION, true);

        $config = $dbParams;
        unset($config['dbname'], $config['path'], $config['url']);

        switch($dbParams['driver']) {
            case 'pdo_pgsql':
            case 'pdo_mysql':
                $tmpConnection = DriverManager::getConnection($config);

                if (in_array($dbParams['dbname'], $tmpConnection->getSchemaManager()->listDatabases())) {
                    return ;
                }

                $tmpConnection->getSchemaManager()->createDatabase($dbParams['dbname']);
                $tmpConnection->close();
                break;
        }
    }
}