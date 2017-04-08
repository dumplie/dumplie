<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application\Extension;

use Dumplie\Customer\Application\Command\AddToCart;
use Dumplie\Customer\Application\Command\AddToCartHandler;
use Dumplie\Customer\Application\Command\ChangeBillingAddress;
use Dumplie\Customer\Application\Command\ChangeBillingAddressHandler;
use Dumplie\Customer\Application\Command\ChangeShippingAddress;
use Dumplie\Customer\Application\Command\ChangeShippingAddressHandler;
use Dumplie\Customer\Application\Command\CreateCart;
use Dumplie\Customer\Application\Command\CreateCartHandler;
use Dumplie\Customer\Application\Command\NewCheckout;
use Dumplie\Customer\Application\Command\NewCheckoutHandler;
use Dumplie\Customer\Application\Command\PlaceOrder;
use Dumplie\Customer\Application\Command\PlaceOrderHandler;
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

        $serviceLocator->get(Services::KERNEL_COMMAND_HANDLER_MAP)->register(
            NewCheckout::class,
            $serviceLocator->get(CustomerServices::CUSTOMER_NEW_CHECKOUT_HANDLER)
        );

        $serviceLocator->get(Services::KERNEL_COMMAND_HANDLER_MAP)->register(
            ChangeBillingAddress::class,
            $serviceLocator->get(CustomerServices::CUSTOMER_CHANGE_BILLING_ADDRESS_HANDLER)
        );

        $serviceLocator->get(Services::KERNEL_COMMAND_HANDLER_MAP)->register(
            ChangeShippingAddress::class,
            $serviceLocator->get(CustomerServices::CUSTOMER_CHANGE_SHIPPING_ADDRESS_HANDLER)
        );

        $serviceLocator->get(Services::KERNEL_COMMAND_HANDLER_MAP)->register(
            PlaceOrder::class,
            $serviceLocator->get(CustomerServices::CUSTOMER_PLACE_ORDER_HANDLER)
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

        $serviceContainer->register(
            CustomerServices::CUSTOMER_NEW_CHECKOUT_HANDLER,
            new ServiceContainer\Definition(
                NewCheckoutHandler::class,
                [
                    new ServiceContainer\ArgumentService(CustomerServices::CUSTOMER_DOMAIN_CHECKOUTS),
                    new ServiceContainer\ArgumentService(CustomerServices::CUSTOMER_DOMAIN_CARTS)
                ]
            )
        );

        $serviceContainer->register(
            CustomerServices::CUSTOMER_CHANGE_BILLING_ADDRESS_HANDLER,
            new ServiceContainer\Definition(
                ChangeBillingAddressHandler::class,
                [
                    new ServiceContainer\ArgumentService(CustomerServices::CUSTOMER_DOMAIN_CHECKOUTS),
                ]
            )
        );

        $serviceContainer->register(
            CustomerServices::CUSTOMER_CHANGE_SHIPPING_ADDRESS_HANDLER,
            new ServiceContainer\Definition(
                ChangeShippingAddressHandler::class,
                [
                    new ServiceContainer\ArgumentService(CustomerServices::CUSTOMER_DOMAIN_CHECKOUTS),
                ]
            )
        );

        $serviceContainer->register(
            CustomerServices::CUSTOMER_PLACE_ORDER_HANDLER,
            new ServiceContainer\Definition(
                PlaceOrderHandler::class,
                [
                    new ServiceContainer\ArgumentService(CustomerServices::CUSTOMER_DOMAIN_CARTS),
                    new ServiceContainer\ArgumentService(CustomerServices::CUSTOMER_DOMAIN_PRODUCTS),
                    new ServiceContainer\ArgumentService(CustomerServices::CUSTOMER_DOMAIN_CHECKOUTS),
                    new ServiceContainer\ArgumentService(CustomerServices::CUSTOMER_DOMAIN_ORDERS),
                    new ServiceContainer\ArgumentService(Services::KERNEL_EVENT_LOG)
                ]
            )
        );
    }
}
