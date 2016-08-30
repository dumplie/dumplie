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
     * @var Schema
     */
    private $schema;

    /**
     * @return Storage
     */
    abstract public function createStorage() : Storage;

    public function setUp()
    {
        $this->storage = $this->createStorage();

        $hydrator = new DefaultHydrator($this->storage);

        $this->schema = new Schema("boolean");

        $productSchema = new Schema\TypeSchema("test", [
            "without_default" => new BoolField(),
            "with_default" => new BoolField(false)
        ]);
        $this->schema->add($productSchema);

        $this->registry = new MetadataAccessRegistry($this->storage, $this->schema, $hydrator);

        $this->storage->alter($this->schema);
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
        $this->storage->drop($this->schema);
    }
}