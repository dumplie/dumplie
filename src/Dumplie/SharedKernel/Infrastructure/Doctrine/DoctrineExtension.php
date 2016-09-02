<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\Doctrine;

use Dumplie\SharedKernel\Application\Exception\ServiceContainer\ServiceNotFoundException;
use Dumplie\SharedKernel\Application\Extension;
use Dumplie\SharedKernel\Application\ServiceContainer;
use Dumplie\SharedKernel\Application\ServiceLocator;
use Dumplie\SharedKernel\Application\Services;
use Dumplie\SharedKernel\Infrastructure\Doctrine\ORM\Application\Transaction\ORMFactory;

final class DoctrineExtension implements Extension
{
    /**
     * @var string
     */
    private $entityManagerServiceId;

    /**
     * @param string $entityManagerServiceId
     */
    public function __construct(string $entityManagerServiceId)
    {
        $this->entityManagerServiceId = $entityManagerServiceId;
    }

    /**
     * @return array
     */
    public function dependsOn() : array
    {
        return [
            Extension\CoreExtension::class
        ];
    }

    /**
     * @param ServiceContainer $serviceContainer
     * @throws ServiceNotFoundException
     */
    public function build(ServiceContainer $serviceContainer)
    {
        $serviceContainer->register(
            Services::KERNEL_TRANSACTION_FACTORY,
            new ServiceContainer\Definition(
                ORMFactory::class,
                [
                    new ServiceContainer\ArgumentService($this->entityManagerServiceId)
                ]
            )
        );
    }

    /**
     * @param ServiceLocator $serviceLocator
     */
    public function boot(ServiceLocator $serviceLocator)
    {
    }
}