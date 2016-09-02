<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Tests\Integration\Generic;

use Dumplie\Metadata\Hydrator\DefaultHydrator;
use Dumplie\Metadata\Metadata;
use Dumplie\Metadata\MetadataAccessRegistry;
use Dumplie\Metadata\MetadataId;
use Dumplie\Metadata\Schema;
use Dumplie\Metadata\Schema\Field\BoolField;
use Dumplie\Metadata\Storage;

abstract class BooleanTypeTestCase extends \PHPUnit_Framework_TestCase
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
     * @return Storage
     */
    abstract public function createStorage() : Storage;

    public function setUp()
    {
        $this->storage = $this->createStorage();

        $hydrator = new DefaultHydrator($this->storage);

        $this->schemaBuilder = new Schema\Builder("boolean");

        $productSchema = new Schema\TypeSchema("test", [
            "without_default" => new BoolField(),
            "with_default" => new BoolField(false)
        ]);
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
        $this->assertEquals(false, $metadata->with_default);
    }


    public function test_updating_metadata()
    {
        $mao = $this->registry->getMAO("test");

        $id = MetadataId::generate();
        $mao->save(new Metadata($id, "test", []));

        $metadata = $mao->getBy(['id' => (string) $id]);

        $metadata->without_default = true;

        $mao->save($metadata);

        $metadata = $mao->getBy(['id' => (string) $id]);

        $this->assertEquals(true, $metadata->without_default);
    }

    public function tearDown()
    {
        $this->storage->drop($this->schemaBuilder->build());
    }
}