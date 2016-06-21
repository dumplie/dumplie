<?php

namespace Dumplie\Test\Integration\Application\InMemory\Metadata;

use Dumplie\Application\Exception\Metadata\NotFoundException;
use Dumplie\Application\Metadata\Hydrator\DefaultHydrator;
use Dumplie\Application\Metadata\Metadata;
use Dumplie\Application\Metadata\MetadataAccessRegistry;
use Dumplie\Application\Metadata\MetadataId;
use Dumplie\Application\Metadata\Schema;
use Dumplie\Application\Metadata\Storage;
use Dumplie\Infrastructure\InMemory\Metadata\InMemoryStorage;

class MetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var MetadataAccessRegistry
     */
    private $registry;

    public function setUp()
    {
        $hydrator = new DefaultHydrator();

        $schema = new Schema();

        $schema->add(new Schema\TypeSchema("product", [
            "sku" => new Schema\Field\TextField(),
            "name" => new Schema\Field\TextField(),
            "brand" => new Schema\Field\TextField("Dumplie")
        ]));

        $this->storage = new InMemoryStorage();
        $this->registry = new MetadataAccessRegistry($this->storage, $schema, $hydrator);
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
        $mao->save(new Metadata($id, "product", [
            "sku" => "DUMPLIE_SKU_1",
            "name" => "Super Product"
        ]));

        $this->assertTrue($this->storage->has("product", (string) $id));

        $metadata = $mao->findBy(["sku" => "DUMPLIE_SKU_1"]);

        $this->assertEquals($id, $metadata->id());
        $this->assertEquals("DUMPLIE_SKU_1", $metadata->sku);
        $this->assertEquals("Super Product", $metadata->name);
        $this->assertEquals("Dumplie", $metadata->brand);
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

        $metadata->brand = "Dumplie Extra";

        $mao->save($metadata);

        $metadata = $mao->findBy(["sku" => "DUMPLIE_SKU_1"]);
        $this->assertEquals("Dumplie Extra", $metadata->brand);
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

        $this->assertFalse($this->storage->has("product", (string) $id));
    }
}
