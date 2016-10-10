<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Tests\Integration\Doctrine;

use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\DoctrineStorage;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\TypeRegistry;
use Dumplie\Metadata\Storage;
use Dumplie\Metadata\Tests\Integration\Generic\DecimalTypeTestCase;
use Dumplie\SharedKernel\Tests\Doctrine\DBALHelper;

class DecimalTypeTest extends DecimalTypeTestCase
{
    use DBALHelper;

    public static function setUpBeforeClass()
    {
        self::createDatabase();
    }

    /**
     * @return Storage
     */
    public function createStorage() : Storage
    {
        return new DoctrineStorage(self::createConnection(), TypeRegistry::withDefaultTypes());
    }
}
