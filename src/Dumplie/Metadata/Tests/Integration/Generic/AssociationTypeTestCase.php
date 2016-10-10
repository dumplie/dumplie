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

abstract class AssociationTypeTestCase extends \PHPUnit_Framework_TestCase
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

        $this->schemaBuilder = new Schema\Builder("association");

        $categorySchema = new Schema\TypeSchema(
            "category",
            [
                "name" => new TextField(),
            ]
        );
        $this->schemaBuilder->addType($categorySchema);

        $this->schemaBuilder->addType(
            new Schema\TypeSchema(
                "product",
                [
                    "category" => new AssociationField('association', $categorySchema, true)
                ]
            )
        );

        $this->registry = new MetadataAccessRegistry($this->storage, $this->schemaBuilder, $hydrator);

        $this->storage->alter($this->schemaBuilder->build());
    }

    public function test_adding_new_metadata()
    {
        $mao = $this->registry->getMAO("product");

        $productId = MetadataId::generate();
        $categoryId = MetadataId::generate();
        $mao->save(
            new Metadata(
                $productId,
                "product",
                [
                    "category" => new Metadata(
                        $categoryId,
                        'category',
                        [
                            "name" => "Fancy stuff"
                        ]
                    )
                ]
            )
        );

        $this->assertTrue($this->storage->has("association", "product", (string) $productId));
        $this->assertTrue($this->storage->has("association", "category", (string) $categoryId));

        $metadata = $mao->getBy(["id" => (string) $productId]);

        $this->assertEquals($productId, $metadata->id());
        $this->assertEquals($categoryId, $metadata->category->id());
        $this->assertEquals("Fancy stuff", $metadata->category->name);
    }

    public function test_updating_metadata()
    {
        $mao = $this->registry->getMAO("product");

        $productId = MetadataId::generate();
        $oldCategoryId = MetadataId::generate();

        $mao->save(
            new Metadata(
                $productId,
                "product",
                [
                    "category" => new Metadata(
                        $oldCategoryId,
                        'category',
                        [
                            "name" => "Fancy stuff"
                        ]
                    )
                ]
            )
        );

        $metadata = $mao->getBy(["id" => (string) $productId]);

        $newCategoryId = MetadataId::generate();
        $metadata->category = new Metadata(
            $newCategoryId,
            'category',
            [
                "name" => "Fashionable"
            ]
        );

        $mao->save($metadata);

        $metadata = $mao->getBy(["id" => (string) $productId]);

        $this->assertEquals($productId, $metadata->id());
        $this->assertEquals($newCategoryId, $metadata->category->id());
        $this->assertEquals("Fashionable", $metadata->category->name);
    }

    public function test_updating_metadata_with_altered_object()
    {
        $mao = $this->registry->getMAO("product");

        $productId = MetadataId::generate();
        $categoryId = MetadataId::generate();

        $mao->save(
            new Metadata(
                $productId,
                "product",
                [
                    "category" => new Metadata(
                        $categoryId,
                        'category', [
                            "name" => "Fancy stuff"
                        ]
                    )
                ]
            )
        );

        $metadata = $mao->getBy(["id" => (string) $productId]);
        $metadata->category->name = "Fashionable";

        $mao->save($metadata);

        $metadata = $mao->getBy(["id" => (string) $productId]);

        $this->assertEquals($productId, $metadata->id());
        $this->assertEquals($categoryId, $metadata->category->id());
        $this->assertEquals("Fashionable", $metadata->category->name);
    }

    public function test_removing_metadata()
    {
        $mao = $this->registry->getMAO("product");

        $productId = MetadataId::generate();
        $categoryId = MetadataId::generate();
        $mao->save(
            new Metadata(
                $productId, "product", [
                              "category" => new Metadata(
                                  $categoryId,
                                  'category',
                                  [
                                      "name" => "Fancy stuff"
                                  ]
                              )
                          ]
            )
        );

        $mao->delete($productId);

        $this->assertFalse($this->storage->has("association", "product", (string) $productId));
        $this->assertTrue($this->storage->has("association", "category", (string) $categoryId));
    }

    public function tearDown()
    {
        $this->storage->drop($this->schemaBuilder->build());
    }
}
