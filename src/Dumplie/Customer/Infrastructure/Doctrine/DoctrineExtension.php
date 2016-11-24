<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Infrastructure\Doctrine;

use Dumplie\Customer\Application\Extension\CoreExtension;
use Dumplie\Customer\Application\Services;
use Dumplie\Customer\Infrastructure\Doctrine\Dbal\Domain\DbalProducts;
use Dumplie\Customer\Infrastructure\Doctrine\Dbal\Query\DbalCartQuery;
use Dumplie\Customer\Infrastructure\Doctrine\ORM\Domain\ORMCarts;
use Dumplie\SharedKernel\Application\Extension;
use Dumplie\SharedKernel\Application\ServiceContainer;
use Dumplie\SharedKernel\Application\ServiceContainer\ArgumentService;
use Dumplie\SharedKernel\Application\ServiceLocator;

final class DoctrineExtension implements Extension
{
    /**
     * @var string
     */
    private $entityManagerServiceId;

    /**
     * @var string
     */
    private $connectionServiceId;

    /**
     * @param string $entityManagerServiceId
     * @param string $connectionServiceId
     */
    public function __construct(string $entityManagerServiceId, string $connectionServiceId)
    {
        $this->connectionServiceId = $connectionServiceId;
        $this->entityManagerServiceId = $entityManagerServiceId;
    }

    public function dependsOn() : array
    {
        return [
            CoreExtension::class,
            Extension\CoreExtension::class
        ];
    }

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function build(ServiceContainer $serviceContainer)
    {
        $serviceContainer->register(
            Services::CUSTOMER_CART_QUERY,
            new ServiceContainer\Definition(DbalCartQuery::class, [
                new ServiceContainer\ArgumentService($this->connectionServiceId),
                new ServiceContainer\ArgumentService(\Dumplie\SharedKernel\Application\Services::KERNEL_METADATA_ACCESS_REGISTRY)
            ])
        );

        $serviceContainer->register(
            Services::CUSTOMER_DOMAIN_PRODUCTS,
            new ServiceContainer\Definition(DbalProducts::class, [
                new ServiceContainer\ArgumentService($this->connectionServiceId),
            ])
        );

        $serviceContainer->register(
            Services::CUSTOMER_DOMAIN_CARTS,
            new ServiceContainer\Definition(ORMCarts::class, [
                    new ArgumentService($this->entityManagerServiceId)
                ]
            )
        );
    }

    public function boot(ServiceLocator $serviceLocator)
    {
    }
}
