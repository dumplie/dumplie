<?php

namespace Dumplie\Test\Integration\Application\Doctrine\Customer;

use Doctrine\ORM\EntityManager;
use Dumplie\Application\Command\Customer\AddToCart;
use Dumplie\Application\Command\Customer\AddToCartHandler;
use Dumplie\Application\Command\Customer\CreateCart;
use Dumplie\Application\Command\Customer\CreateCartHandler;
use Dumplie\Application\Command\Customer\RemoveFromCart;
use Dumplie\Application\Command\Customer\RemoveFromCartHandler;
use Dumplie\Application\Command\Inventory\CreateProduct;
use Dumplie\Application\Command\Inventory\CreateProductHandler;
use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Carts;
use Dumplie\Domain\Customer\Products;
use Dumplie\Infrastructure\Doctrine\Dbal\Implementation\Domain\Customer\DbalProducts;
use Dumplie\Infrastructure\Doctrine\ORM\Implementation\Application\Transaction\Factory;
use Dumplie\Infrastructure\Doctrine\ORM\Implementation\Domain\Customer\DoctrineCarts;
use Dumplie\Infrastructure\Doctrine\ORM\Implementation\Domain\Inventory\DoctrineProducts as DoctrineProductsInventory;
use Dumplie\Test\Doctrine\ORMTestCase;

class CartTest extends ORMTestCase
{
    /**
     * @var Carts
     */
    private $carts;

    /**
     * @var Products
     */
    private $products;

    /**
     * @var DoctrineProductsInventory
     */
    private $inventory;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function setUp()
    {
        $this->createDatabase();
        $this->entityManager = $this->createEntityManager();
        $this->createSchema($this->entityManager);

        $this->carts = new DoctrineCarts($this->entityManager);
        $this->products = new DbalProducts($this->entityManager->getConnection());
        $this->inventory = new DoctrineProductsInventory($this->entityManager);

        $this->addProductToInventory("SKU_1", 1000);
    }

    function test_adding_new_products_to_cart()
    {
        $cartId = $this->createNewCart($this->entityManager, 'EUR');

        $command = new AddToCart('SKU_1', 2, (string) $cartId);
        $handler = new AddToCartHandler($this->products, $this->carts);

        $transaction = (new Factory($this->entityManager))->open();
        $handler->handle($command);
        $transaction->commit();

        $this->entityManager->clear();

        $this->assertCount(1, $this->carts->getById($cartId)->items());
    }

    function test_removing_products_from_cart()
    {
        $cartId = $this->createNewCart($this->entityManager, 'EUR');

        $addCommand = new AddToCart('SKU_1', 2, (string) $cartId);
        $addHandler = new AddToCartHandler($this->products, $this->carts);
        $transaction = (new Factory($this->entityManager))->open();
        $addHandler->handle($addCommand);
        $transaction->commit();

        $removeCommand = new RemoveFromCart((string) $cartId, 'SKU_1');
        $removeHandler = new RemoveFromCartHandler($this->carts);
        $transaction = (new Factory($this->entityManager))->open();
        $removeHandler->handle($removeCommand);
        $transaction->commit();

        $this->entityManager->clear();

        $cart = $this->carts->getById($cartId);

        $this->assertTrue($cart->isEmpty());
    }

    public function tearDown()
    {
        $this->dropSchema($this->entityManager);
    }

    private function createNewCart(EntityManager $em, $currency = 'EUR')
    {
        $cartId = CartId::generate();
        $command = new CreateCart((string) $cartId, $currency);
        $handler = new CreateCartHandler($this->carts);

        $transaction = (new Factory($em))->open();

        $handler->handle($command);

        $transaction->commit();

        return $cartId;
    }

    protected function addProductToInventory($sku, $price, $currency = "EUR", $available = true)
    {
        $command = new CreateProduct($sku, $price, $currency, $available);
        $handler = new CreateProductHandler($this->inventory);

        $transaction = (new Factory($this->entityManager))->open();

        $handler->handle($command);

        $transaction->commit();
    }
}