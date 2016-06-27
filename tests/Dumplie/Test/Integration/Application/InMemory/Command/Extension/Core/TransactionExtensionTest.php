<?php

namespace Dumplie\Test\Integration\Application\InMemory\Command\Extension\Core;

use Dumplie\Application\Command\Customer\AddToCart;
use Dumplie\Application\Command\Customer\AddToCartHandler;
use Dumplie\Application\Command\Customer\CreateCart;
use Dumplie\Application\Command\Customer\CreateCartHandler;
use Dumplie\Application\Command\Extension\Core\TransactionExtension;
use Dumplie\Application\Command\ExtensionRegistry;
use Dumplie\Application\ServiceLocator;
use Dumplie\Application\Transaction\Transaction;
use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Exception\ProductNotFoundException;
use Dumplie\Infrastructure\InMemory\Customer\InMemoryCarts;
use Dumplie\Infrastructure\InMemory\Customer\InMemoryProducts;
use Dumplie\Infrastructure\InMemory\InMemoryServiceLocator;
use Dumplie\Infrastructure\Tactician\CommandBus;
use Dumplie\Infrastructure\Tactician\Middleware\ExtensionMiddleware;
use Dumplie\Test\Double\Application\Transaction\FactoryStub;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\CommandBus as Tactician;

class TransactionExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceLocator
     */
    private $serviceLocator;

    /**
     * @var ExtensionRegistry
     */
    private $extensionRegistry;

    /**
     * @var CommandBus
     */
    private $commandBus;

    public function setUp()
    {
        $carts = new InMemoryCarts();
        $products = new InMemoryProducts();

        $commandHandlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new InMemoryLocator([
                CreateCart::class => new CreateCartHandler($carts),
                AddToCart::class => new AddToCartHandler($products, $carts)
            ]),
            new HandleInflector()
        );

        $this->serviceLocator = new InMemoryServiceLocator();
        $this->extensionRegistry = new ExtensionRegistry($this->serviceLocator);
        $extensionMiddleware = new ExtensionMiddleware($this->extensionRegistry);

        $this->commandBus = new CommandBus(new Tactician([
            $extensionMiddleware,
            $commandHandlerMiddleware
        ]));
    }

    public function test_transaction_extension_commit()
    {
        $transaction = $this->prophesize(Transaction::class);
        $transaction->commit()->shouldBeCalled();
        $transaction->rollback()->shouldNotBeCalled();

        $factory = new FactoryStub($transaction->reveal());
        $transaction = new TransactionExtension($factory);
        $this->extensionRegistry->register($transaction);

        $command = new CreateCart((string) CartId::generate(), 'PLN');
        $this->commandBus->handle($command);
    }

    public function test_transaction_extension_rollback()
    {
        $transaction = $this->prophesize(Transaction::class);
        $transaction->commit()->shouldNotBeCalled();
        $transaction->rollback()->shouldBeCalled();

        $factory = new FactoryStub($transaction->reveal());
        $transaction = new TransactionExtension($factory);
        $this->extensionRegistry->register($transaction);

        $this->expectException(ProductNotFoundException::class);

        $command = new AddToCart("SKU", 1,(string) CartId::generate());
        $this->commandBus->handle($command);
    }
}
