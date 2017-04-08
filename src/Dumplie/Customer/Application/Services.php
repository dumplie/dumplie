<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application;

final class Services
{
    const CUSTOMER_DOMAIN_PRODUCTS = 'dumplie.customer.query.product';
    const CUSTOMER_DOMAIN_CARTS = 'dumplie.customer.domain.carts';
    const CUSTOMER_DOMAIN_CHECKOUTS = 'dumplie.customer.domain.checkouts';
    const CUSTOMER_DOMAIN_ORDERS = 'dumplie.customer.domain.orders';

    const CUSTOMER_CART_QUERY = 'dumplie.customer.application.cart_query';
    const CUSTOMER_CHECKOUT_QUERY = 'dumplie.customer.application.checkout_query';

    const CUSTOMER_CREATE_CART_HANDLER = 'dumplie.customer.command.handler.create_cart';
    const CUSTOMER_ADD_TO_CART_HANDLER = 'dumplie.customer.command.handler.add_to_cart';
    const CUSTOMER_REMOVE_FROM_CART_HANDLER = 'dumplie.customer.command.handler.remove_from_cart';

    const CUSTOMER_NEW_CHECKOUT_HANDLER = 'dumplie.checkout.command.handler.new_checkout';
    const CUSTOMER_CHANGE_BILLING_ADDRESS_HANDLER = 'dumplie.checkout.command.handler.change_billing_address';
    const CUSTOMER_CHANGE_SHIPPING_ADDRESS_HANDLER = 'dumplie.checkout.command.handler.change_shipping_address';
    const CUSTOMER_PLACE_ORDER_HANDLER = 'dumplie.checkout.command.handler.place_order';
}
