<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\Doctrine\ORM;

use Dumplie\SharedKernel\Application\Exception\ServiceContainer\ServiceNotFoundException;
use Dumplie\SharedKernel\Application\Extension;
use Dumplie\SharedKernel\Application\ServiceContainer;
use Dumplie\SharedKernel\Application\ServiceLocator;
use Dumplie\SharedKernel\Infrastructure\Doctrine\ORM\Application\Transaction\ORMFactory;

final class ORMExtension implements Extension
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
     * @param ServiceContainer $serviceContainer
     * @throws ServiceNotFoundException
     */
    public function configure(ServiceContainer $serviceContainer)
    {
        $serviceContainer->register(
            Extension\CoreExtension::TRANSACTION_FACTORY_SERVICE_ID,
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