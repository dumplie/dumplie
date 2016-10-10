<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Tests\Integration\Generic;

use Dumplie\Metadata\Hydrator\DefaultHydrator;
use Dumplie\Metadata\Metadata;
use Dumplie\Metadata\MetadataAccessRegistry;
use Dumplie\Metadata\MetadataId;
use Dumplie\Metadata\Schema;
use Dumplie\Metadata\Schema\Field\DateTimeField;
use Dumplie\Metadata\Storage;

abstract class DateTimeTypeTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var MetadataAccessRegistry
     */
    protected $registry;

    /**
     * @var Schema\Builder
     */
    private $schemaBuilder;

    /**
     * @var \DateTimeImmutable
     */
    private $defaultDate;

    /**
     * @return Storage
     */
    abstract public function createStorage() : Storage;

    public function setUp()
    {
        $this->defaultDate = new \DateTimeImmutable("2004-02-12T15:19:21+00:00");

        $this->storage = $this->createStorage();

        $hydrator = new DefaultHydrator($this->storage);

        $this->schemaBuilder = new Schema\Builder("date");

        $productSchema = new Schema\TypeSchema(
            "test",
            [
                "without_default" => new DateTimeField(null, true, [], 'Y-m-d H:i:s'),
                "with_default" => new DateTimeField($this->defaultDate, true, [], 'Y-m-d H:i:s')
            ]
        );
        $this->schemaBuilder->addType($productSchema);

        $this->registry = new MetadataAccessRegistry($this->storage, $this->schemaBuilder, $hydrator);

        $this->storage->alter($this->schemaBuilder->build());
    }

    public function test_reading_metadata()
    {
        $mao = $this->registry->getMAO("test");

        $id = MetadataId::generate();
        $mao->save(new Metadata($id, "test", []));

        $metadata = $mao->getBy(['id' => (string) $id]);

        $this->assertEquals(null, $metadata->without_default);
        $this->assertEquals($this->defaultDate->format('U'), $metadata->with_default->format('U'));
    }

    public function test_updating_metadata()
    {
        $mao = $this->registry->getMAO("test");

        $id = MetadataId::generate();
        $mao->save(new Metadata($id, "test", []));

        $metadata = $mao->getBy(['id' => (string) $id]);

        $updatedDate = new \DateTimeImmutable();
        $metadata->without_default = $updatedDate;

        $mao->save($metadata);

        $metadata = $mao->getBy(['id' => (string) $id]);

        $this->assertEquals($updatedDate->format('U'), $metadata->without_default->format('U'));
    }

    public function tearDown()
    {
        $this->storage->drop($this->schemaBuilder->build());
    }
}
