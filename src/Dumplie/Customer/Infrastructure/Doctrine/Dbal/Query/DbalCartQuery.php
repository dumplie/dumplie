<?php
declare (strict_types = 1);

namespace Dumplie\Customer\Infrastructure\Doctrine\Dbal\Query;

use Doctrine\DBAL\Connection;
use Dumplie\Customer\Application\Extension\Metadata;
use Dumplie\Customer\Application\Exception\QueryException;
use Dumplie\Customer\Application\Query\CartQuery;
use Dumplie\Customer\Application\Query\Result\Cart;
use Dumplie\Customer\Application\Query\Result\CartItem;
use Dumplie\Metadata\MetadataAccessObject;
use Dumplie\Metadata\MetadataAccessRegistry;

class DbalCartQuery implements CartQuery
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var MetadataAccessObject
     */
    private $mao;

    /**
     * DbalCartQuery constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection, MetadataAccessRegistry $accessRegistry)
    {
        $this->connection = $connection;
        $this->mao = $accessRegistry->getMAO(Metadata::TYPE_NAME);
    }

    /**
     * @param string $cartId
     *
     * @throws QueryException
     * @return Cart
     */
    public function getById(string $cartId) : Cart
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('id', 'currency', 'cart_items')
            ->from('dumplie_customer_cart')
            ->where('id = :id')
            ->setParameter('id', $cartId);

        $cartData = $this->connection->fetchAssoc($qb->getSQL(), $qb->getParameters());

        if (empty($cartData)) {
            throw QueryException::cartNotFound($cartId);
        }

        $itemsData = json_decode($cartData['cart_items'], true);

        $items = [];
        foreach ($itemsData as $item) {
            $items[] = $this->getItemBySku($item['sku'], $item['quantity']);
        }

        return new Cart($cartData['currency'], $items);
    }

    /**
     * @param string $sku
     * @param int    $quantity
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
    public function doesCartWithIdExist(string $cartId) : bool
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('COUNT(*)')
            ->from('dumplie_customer_cart')
            ->where('id = :id')
            ->setParameter('id', $cartId)
            ->getSQL();

        return (bool) $this->connection->fetchColumn($qb->getSQL(), $qb->getParameters());
    }
}
