<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Application;

final class Services
{
    const CUSTOMER_DOMAIN_PRODUCTS = 'dumplie.customer.query.product';
    const CUSTOMER_DOMAIN_CARTS = 'dumplie.customer.domain.carts';

    const CUSTOMER_CART_QUERY = 'dumplie.customer.application.cart_query';

    const CUSTOMER_CREATE_CART_HANDLER = 'dumplie.customer.command.handler.create_cart';
    const CUSTOMER_ADD_TO_CART_HANDLER = 'dumplie.customer.command.handler.add_to_cart';
    const CUSTOMER_REMOVE_FROM_CART_HANDLER = 'dumplie.customer.command.handler.remove_from_cart';
}
