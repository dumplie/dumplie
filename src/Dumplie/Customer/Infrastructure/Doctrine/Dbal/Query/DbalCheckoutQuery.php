<?php
declare (strict_types = 1);

namespace Dumplie\Customer\Infrastructure\Doctrine\Dbal\Query;

use Doctrine\DBAL\Connection;
use Dumplie\Customer\Application\Extension\Metadata;
use Dumplie\Customer\Application\Exception\QueryException;
use Dumplie\Customer\Application\Query\CartQuery;
use Dumplie\Customer\Application\Query\Result\Address;
use Dumplie\Customer\Application\Query\Result\Cart;
use Dumplie\Customer\Application\Query\Result\CartItem;
use Dumplie\Customer\Application\Query\Result\Checkout;
use Dumplie\Customer\Domain\CartId;
use Dumplie\Metadata\MetadataAccessObject;
use Dumplie\Metadata\MetadataAccessRegistry;

class DbalCheckoutQuery
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * DbalCartQuery constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $cartId
     *
     * @throws QueryException
     * @return Checkout
     */
    public function getById(string $cartId): Checkout
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select(
            'id', 'billing_address_name', 'billing_address_street', 'billing_address_post_code', 'billing_address_city',
            'billing_address_country_iso2code', 'shipping_address_name', 'shipping_address_street',
            'shipping_address_post_code', 'shipping_address_city', 'shipping_address_country_iso2code'
        )
            ->from('dumplie_customer_checkout')
            ->where('id = :id')
            ->setParameter('id', $cartId);

        $checkoutData = $this->connection->fetchAssoc($qb->getSQL(), $qb->getParameters());

        if (empty($checkoutData)) {
            throw QueryException::cartNotFound($cartId);
        }

        return new Checkout(
            new CartId($cartId),
            new Address(
                $checkoutData['billing_address_name'],
                $checkoutData['billing_address_street'],
                $checkoutData['billing_address_post_code'],
                $checkoutData['billing_address_city'],
                $checkoutData['billing_address_country_iso2code']
            ),
            new Address(
                $checkoutData['shipping_address_name'],
                $checkoutData['shipping_address_street'],
                $checkoutData['shipping_address_post_code'],
                $checkoutData['shipping_address_city'],
                $checkoutData['shipping_address_country_iso2code']
            )
        );
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartItem
     * @throws QueryException
     */
    private function getItemBySku(string $sku, int $quantity): CartItem
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from('dumplie_inventory_product')
            ->where('sku = :sku')
            ->setParameter('sku', $sku);

        $itemData = $this->connection->fetchAssoc($qb->getSQL(), $qb->getParameters());

        if (empty($itemData)) {
            throw QueryException::cartItemNotFound($sku);
        }

        return new CartItem(
            $itemData['sku'],
            $quantity,
            $itemData['price_amount'] / $itemData['price_precision'],
            $itemData['price_currency'],
            $this->mao->getBy([Metadata::FIELD_SKU => $itemData['sku']])
        );
    }

    /**
     * @param string $cartId
     *
     * @return bool
     */
    public function doesCartWithIdExist(string $cartId): bool
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('COUNT(*)')
            ->from('dumplie_customer_cart')
            ->where('id = :id')
            ->setParameter('id', $cartId)
            ->getSQL();

        return (bool)$this->connection->fetchColumn($qb->getSQL(), $qb->getParameters());
    }
}
