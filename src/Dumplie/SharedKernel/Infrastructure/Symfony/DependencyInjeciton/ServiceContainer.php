<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\Symfony\DependencyInjeciton;

use Dumplie\SharedKernel\Application\ServiceContainer as BaseContainer;
use Dumplie\SharedKernel\Application\ServiceContainer\Definition;
use Dumplie\SharedKernel\Application\Services;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition as SymfonyDefinition;
use Symfony\Component\DependencyInjection\Reference;

final class ServiceContainer implements BaseContainer
{
    /**
     * @var ContainerBuilder
     */
    private $builder;

    /**
     * @param ContainerBuilder $builder
     */
    public function __construct(ContainerBuilder $builder)
    {
        $this->builder = $builder;
        $this->builder->setAlias(Services::KERNEL_SERVICE_LOCATOR, 'service_container');
    }

    /**
     * @param $id
     * @param Definition $definition
     */
    public function register(string $id, Definition $definition)
    {
        $createArguments = function(BaseContainer\Argument $argument) use (&$createArguments) {
            if ($argument instanceof BaseContainer\ScalarArgument) {
                return $argument->value();
            }

            if ($argument instanceof BaseContainer\ArgumentService) {
                return new Reference($argument->value());
            }

            if ($argument instanceof BaseContainer\ArgumentCollection) {
                return array_map($createArguments, $argument->value());
            }
        };

        $this->builder->setDefinition($id, new SymfonyDefinition(
            $definition->className(),
            array_map($createArguments, $definition->arguments())
        ));
    }

    /**
     * @param string $id
     * @return bool
     */
    public function definitionExists(string $id) : bool
    {
        return $this->builder->hasDefinition($id) || $this->builder->hasAlias($id);
    }

    /**
     * @param string $id
     * @return string
     */
    public function definitionClass(string $id) : string
    {
        if ($this->builder->hasAlias($id)) {
            return $this->builder->getDefinition((string) $this->builder->getAlias($id))->getClass()->getClass();
        }

        return $this->builder->getDefinition($id)->getClass();
    }
}