<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Tests\Integration\Symfony\Form\Type;

use Dumplie\Metadata\Hydrator\DefaultHydrator;
use Dumplie\Metadata\Infrastructure\InMemory\InMemoryStorage;
use Dumplie\Metadata\Infrastructure\Symfony\Form\Type\MetadataType;
use Dumplie\Metadata\MetadataAccessRegistry;
use Dumplie\Metadata\Schema;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Forms;
use Symfony\Component\Validator\Validation;

final class MetadataTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FormFactory
     */
    private $factory;

    /**
     * @var Schema\Builder
     */
    private $builder;

    /**
     * @var MetadataAccessRegistry
     */
    private $registry;

    public function setUp()
    {
        $factoryBuilder = Forms::createFormFactoryBuilder();
        $factoryBuilder->addExtension(new ValidatorExtension(Validation::createValidator()));

        $this->factory = $factoryBuilder->getFormFactory();

        $storage = new InMemoryStorage();
        $this->builder = new Schema\Builder();
        $this->registry = new MetadataAccessRegistry($storage, $this->builder, new DefaultHydrator($storage));
    }

    public function test_building_text_type_form()
    {
        $this->builder->addType(new Schema\TypeSchema("form", [
            'text_field' => new Schema\Field\TextField()
        ]));

        $form = $this->factory->create(MetadataType::class, null, [
            'mao' => $this->registry->getMAO('form')
        ]);

        $this->assertTrue($form->has('text_field'));
        $this->assertTrue($form->get('text_field')->isRequired());
        $this->assertInstanceOf(TextType::class, $form->get('text_field')->getConfig()->getType()->getInnerType());
    }

    public function test_building_checkbox_type_form()
    {
        $this->builder->addType(new Schema\TypeSchema("form", [
            'bool_field' => new Schema\Field\BoolField()
        ]));

        $form = $this->factory->create(MetadataType::class, null, [
            'mao' => $this->registry->getMAO('form')
        ]);

        $this->assertTrue($form->has('bool_field'));
        $this->assertTrue($form->get('bool_field')->isRequired());
        $this->assertInstanceOf(CheckboxType::class, $form->get('bool_field')->getConfig()->getType()->getInnerType());
    }

    public function test_passing_options_to_form_type()
    {
        $this->builder->addType(new Schema\TypeSchema("form", [
            'bool_field' => new Schema\Field\BoolField()
        ]));

        $form = $this->factory->create(MetadataType::class, null, [
            'mao' => $this->registry->getMAO('form'),
            'type_options' => [
                'bool_field' => ['label' => 'Boolean']
            ]
        ]);

        $this->assertEquals('Boolean', $form->get('bool_field')->getConfig()->getOption('label'));
    }

    public function test_changing_default_form_type()
    {
        $this->builder->addType(new Schema\TypeSchema("form", [
            'text_field' => new Schema\Field\TextField()
        ]));

        $form = $this->factory->create(MetadataType::class, null, [
            'mao' => $this->registry->getMAO('form'),
            'type_forms' => [
                'text_field' => TextareaType::class
            ]
        ]);

        $this->assertInstanceOf(TextareaType::class, $form->get('text_field')->getConfig()->getType()->getInnerType());
    }
}