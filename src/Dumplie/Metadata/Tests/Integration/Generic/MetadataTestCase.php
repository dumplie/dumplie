<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Tests\Integration\Generic;

use Dumplie\Metadata\Schema\Field\AssociationField;
use Dumplie\Metadata\Schema\Field\BoolField;
use Dumplie\Metadata\Schema\Field\TextField;
use Dumplie\Metadata\Hydrator\DefaultHydrator;
use Dumplie\Metadata\Metadata;
use Dumplie\Metadata\MetadataAccessRegistry;
use Dumplie\Metadata\MetadataId;
use Dumplie\Metadata\Schema;
use Dumplie\Metadata\Storage;
use Dumplie\Metadata\Exception\NotFoundException;

abstract class MetadataTestCase extends \PHPUnit_Framework_TestCase
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

        $this->schemaBuilder = new Schema\Builder("inventory");

        $this->schemaBuilder->addType(new Schema\TypeSchema("product", [
            "sku" => new TextField(),
            "name" => new TextField(),
        ]));

        $this->registry = new MetadataAccessRegistry($this->storage, $this->schemaBuilder, $hydrator);

        $this->storage->alter($this->schemaBuilder->build());
    }

    public function test_getting_not_existing_moa()
    {
        $this->expectException(NotFoundException::class);
        $this->registry->getMAO("order");
    }

    public function test_getting_not_existing_metadata()
    {
        $this->expectException(NotFoundException::class);

        $mao = $this->registry->getMAO("product");

        $mao->getBy(["sku" => "SKU"]);
    }

    public function test_adding_new_metadata()
    {
        $mao = $this->registry->getMAO("product");

        $id = MetadataId::generate();
        $mao->save(
            new Metadata(
                $id,
                "product",
                [
                    "sku" => "DUMPLIE_SKU_1",
                    "name" => "Super Product"
                ]
            )
        );

        $this->assertTrue($this->storage->has("inventory", "product", (string) $id));

        $metadata = $mao->findBy(["sku" => "DUMPLIE_SKU_1"]);

        $this->assertEquals($id, $metadata->id());
        $this->assertEquals("DUMPLIE_SKU_1", $metadata->sku);
        $this->assertEquals("Super Product", $metadata->name);
    }

    public function test_updating_metadata()
    {
        $mao = $this->registry->getMAO("product");

        $id = MetadataId::generate();
        $mao->save(new Metadata($id, "product", [
            "sku" => "DUMPLIE_SKU_1",
            "name" => "Super Product"
        ]));

        $metadata = $mao->findBy(["sku" => "DUMPLIE_SKU_1"]);

        $metadata->name = "Super Product Deluxe";

        $mao->save($metadata);

        $metadata = $mao->findBy(["sku" => "DUMPLIE_SKU_1"]);
        $this->assertEquals("Super Product Deluxe", $metadata->name);
    }

    public function test_removing_metadata()
    {
        $mao = $this->registry->getMAO("product");

        $id = MetadataId::generate();
        $mao->save(new Metadata($id, "product", [
            "sku" => "DUMPLIE_SKU_1",
            "name" => "Super Product"
        ]));

        $mao->delete($id);

        $this->assertFalse($this->storage->has("inventory", "product", (string) $id));
    }

    public function tearDown()
    {
        $this->storage->drop($this->schemaBuilder->build());
    }
}
