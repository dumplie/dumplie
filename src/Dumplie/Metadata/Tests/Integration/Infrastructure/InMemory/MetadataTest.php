<?php

namespace Dumplie\Metadata\Tests\Integration\Infrastructure\InMemory;

use Dumplie\Metadata\Application\Schema\Field\AssociationField;
use Dumplie\Metadata\Application\Schema\Field\TextField;
use Dumplie\Metadata\Application\Hydrator\DefaultHydrator;
use Dumplie\Metadata\Application\Metadata;
use Dumplie\Metadata\Application\MetadataAccessRegistry;
use Dumplie\Metadata\Application\MetadataId;
use Dumplie\Metadata\Application\Schema;
use Dumplie\Metadata\Application\Storage;
use Dumplie\Metadata\Infrastructure\InMemory\InMemoryStorage;
use Dumplie\Metadata\Application\Exception\NotFoundException;

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
        $this->storage = new InMemoryStorage();

        $hydrator = new DefaultHydrator($this->storage);

        $schema = new Schema("inventory");

        $categorySchema = new Schema\TypeSchema("category", [
            "name" => new TextField(),
        ]);
        $schema->add($categorySchema);

        $productSchema = new Schema\TypeSchema("product", [
            "sku" => new TextField(),
            "name" => new TextField(),
            "brand" => new TextField("Dumplie"),
            "category" => new AssociationField('inventory', $categorySchema, true)
        ]);
        $schema->add($productSchema);

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
        $this->assertEquals("Dumplie", $metadata->brand);
    }

    public function test_adding_new_metadata_with_association()
    {
        $mao = $this->registry->getMAO("product");

        $productId = MetadataId::generate();
        $categoryId = MetadataId::generate();
        $mao->save(new Metadata($productId, "product", [
            "sku" => "DUMPLIE_SKU_1",
            "name" => "Super Product",
            "category" => new Metadata($categoryId, 'category', [
                "name" => "Fancy stuff"
            ])
        ]));

        $this->assertTrue($this->storage->has("inventory", "product", (string) $productId));
        $this->assertTrue($this->storage->has("inventory", "category", (string) $categoryId));

        $metadata = $mao->findBy(["sku" => "DUMPLIE_SKU_1"]);

        $this->assertEquals($productId, $metadata->id());
        $this->assertEquals($categoryId, $metadata->category->id());
        $this->assertEquals("Fancy stuff", $metadata->category->name);
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

    public function test_updating_metadata_with_association_with_new_object()
    {
        $mao = $this->registry->getMAO("product");

        $productId = MetadataId::generate();
        $oldCategoryId = MetadataId::generate();

        $mao->save(new Metadata($productId, "product", [
            "sku" => "DUMPLIE_SKU_1",
            "name" => "Super Product",
            "category" => new Metadata($oldCategoryId, 'category', [
                "name" => "Fancy stuff"
            ])
        ]));

        $metadata = $mao->findBy(["sku" => "DUMPLIE_SKU_1"]);

        $newCategoryId = MetadataId::generate();
        $metadata->category = new Metadata($newCategoryId, 'category', [
            "name" => "Fashionable"
        ]);

        $mao->save($metadata);

        $metadata = $mao->findBy(["sku" => "DUMPLIE_SKU_1"]);
        $this->assertEquals($productId, $metadata->id());
        $this->assertEquals($newCategoryId, $metadata->category->id());
        $this->assertEquals("Fashionable", $metadata->category->name);
    }

    public function test_updating_metadata_with_association_with_altered_object()
    {
        $mao = $this->registry->getMAO("product");

        $productId = MetadataId::generate();
        $categoryId = MetadataId::generate();

        $mao->save(new Metadata($productId, "product", [
            "sku" => "DUMPLIE_SKU_1",
            "name" => "Super Product",
            "category" => new Metadata($categoryId, 'category', [
                "name" => "Fancy stuff"
            ])
        ]));

        $metadata = $mao->findBy(["sku" => "DUMPLIE_SKU_1"]);
        $metadata->category->name ="Fashionable";

        $mao->save($metadata);

        $metadata = $mao->findBy(["sku" => "DUMPLIE_SKU_1"]);
        $this->assertEquals($productId, $metadata->id());
        $this->assertEquals($categoryId, $metadata->category->id());
        $this->assertEquals("Fashionable", $metadata->category->name);
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

    public function test_removing_metadata_with_association()
    {
        $mao = $this->registry->getMAO("product");

        $productId = MetadataId::generate();
        $categoryId = MetadataId::generate();
        $mao->save(new Metadata($productId, "product", [
            "sku" => "DUMPLIE_SKU_1",
            "name" => "Super Product",
            "category" => new Metadata($categoryId, 'category', [
                "name" => "Fancy stuff"
            ])
        ]));

        $mao->delete($productId);

        $this->assertFalse($this->storage->has("inventory", "product", (string) $productId));
        $this->assertTrue($this->storage->has("inventory", "category", (string) $categoryId));

    }
}
