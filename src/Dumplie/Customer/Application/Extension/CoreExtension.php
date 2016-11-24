<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Extension;

use Dumplie\Customer\Application\Command\AddToCart;
use Dumplie\Customer\Application\Command\AddToCartHandler;
use Dumplie\Customer\Application\Command\CreateCart;
use Dumplie\Customer\Application\Command\CreateCartHandler;
use Dumplie\Customer\Application\Command\RemoveFromCart;
use Dumplie\Customer\Application\Command\RemoveFromCartHandler;
use Dumplie\SharedKernel\Application\Extension;
use Dumplie\SharedKernel\Application\ServiceContainer;
use Dumplie\SharedKernel\Application\ServiceLocator;
use Dumplie\Customer\Application\Services as CustomerServices;
use Dumplie\SharedKernel\Application\Services;

final class CoreExtension implements Extension
{
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
     */
    public function build(ServiceContainer $serviceContainer)
    {
        $this->registerCommandHandler($serviceContainer);
    }

    /**
     * @param ServiceLocator $serviceLocator
     */
    public function boot(ServiceLocator $serviceLocator)
    {
        $this->mapCommands($serviceLocator);
    }

    /**
     * @param ServiceLocator $serviceLocator
     */
    protected function mapCommands(ServiceLocator $serviceLocator)
    {
        $serviceLocator->get(Services::KERNEL_COMMAND_HANDLER_MAP)->register(
            CreateCart::class,
            $serviceLocator->get(CustomerServices::CUSTOMER_CREATE_CART_HANDLER)
        );

        $serviceLocator->get(Services::KERNEL_COMMAND_HANDLER_MAP)->register(
            AddToCart::class,
            $serviceLocator->get(CustomerServices::CUSTOMER_ADD_TO_CART_HANDLER)
        );

        $serviceLocator->get(Services::KERNEL_COMMAND_HANDLER_MAP)->register(
            RemoveFromCart::class,
            $serviceLocator->get(CustomerServices::CUSTOMER_REMOVE_FROM_CART_HANDLER)
        );
    }

    /**
     * @param ServiceContainer $serviceContainer
     */
    protected function registerCommandHandler(ServiceContainer $serviceContainer)
    {
        $serviceContainer->register(
            CustomerServices::CUSTOMER_CREATE_CART_HANDLER,
            new ServiceContainer\Definition(
                CreateCartHandler::class,
                [
                    new ServiceContainer\ArgumentService(CustomerServices::CUSTOMER_DOMAIN_CARTS)
                ]
            )
        );


        $serviceContainer->register(
            CustomerServices::CUSTOMER_ADD_TO_CART_HANDLER,
            new ServiceContainer\Definition(
                AddToCartHandler::class,
                [
                    new ServiceContainer\ArgumentService(CustomerServices::CUSTOMER_DOMAIN_PRODUCTS),
                    new ServiceContainer\ArgumentService(CustomerServices::CUSTOMER_DOMAIN_CARTS)
                ]
            )
        );

        $serviceContainer->register(
            CustomerServices::CUSTOMER_REMOVE_FROM_CART_HANDLER,
            new ServiceContainer\Definition(
                RemoveFromCartHandler::class,
                [
                    new ServiceContainer\ArgumentService(CustomerServices::CUSTOMER_DOMAIN_CARTS)
                ]
            )
        );
    }
}
