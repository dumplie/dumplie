<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Infrastructure\Symfony\Form\Type;

use Dumplie\Metadata\Metadata;
use Dumplie\Metadata\MetadataAccessObject;
use Dumplie\Metadata\Schema\FieldDefinition;
use Dumplie\Metadata\Schema\Type;
use Dumplie\Metadata\Schema\TypeSchema;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

final class MetadataType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'metadata';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('mao', null);
        $resolver->setDefaults(['data_class' => Metadata::class]);
        $resolver->setDefaults(['type_options' => []]);
        $resolver->setDefaults(['type_forms' => []]);
        $resolver->setAllowedTypes('type_options', ['array']);
        $resolver->setAllowedTypes('mao', [MetadataAccessObject::class]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var TypeSchema $typeSchema */
        $typeSchema = $options['mao']->typeSchema();

        foreach ($typeSchema->getDefinitions() as $fieldName => $definition) {
            switch ((string) $definition->type()) {
                case Type::text():
                    $this->buildTextType($builder, $options, $fieldName, $definition);
                    break;
                case Type::bool():
                    $this->buildBoolType($builder, $options, $fieldName, $definition);
                    break;
            }
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @param FieldDefinition $definition
     */
    protected function buildTextType(FormBuilderInterface $builder, array $options, string $fieldName, FieldDefinition $definition)
    {
        $typeOptions = $options['type_options'];
        $typeForms = $options['type_forms'];
        $isRequired = !$definition->isNullable();

        $options = ['required' => $isRequired];

        if ($isRequired) {
            $options['constraints'] = [new NotBlank()];
        }

        $builder->add(
            $fieldName,
            array_key_exists($fieldName, $typeForms) ? $typeForms[$fieldName] : TextType::class,
            array_key_exists($fieldName, $typeOptions) ? array_merge($options, $typeOptions[$fieldName]) : $options
        );
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @param FieldDefinition $definition
     */
    protected function buildBoolType(FormBuilderInterface $builder, array $options, string $fieldName, FieldDefinition $definition)
    {
        $typeOptions = $options['type_options'];
        $typeForms = $options['type_forms'];

        $builder->add(
            $fieldName,
            array_key_exists($fieldName, $typeForms) ? $typeForms[$fieldName] : CheckboxType::class,
            array_key_exists($fieldName, $typeOptions) ? $typeOptions[$fieldName] : []
        );
    }
}