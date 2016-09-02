<?php

namespace Dumplie\SharedKernel\Tests\Integration\Application\InMemory\Command\Extension\Core;

use Dumplie\Customer\Application\Command\AddToCart;
use Dumplie\Customer\Application\Command\AddToCartHandler;
use Dumplie\Customer\Application\Command\CreateCart;
use Dumplie\Customer\Application\Command\CreateCartHandler;
use Dumplie\SharedKernel\Application\Extension\Command\TransactionExtension;
use Dumplie\SharedKernel\Application\Command\ExtensionRegistry;
use Dumplie\SharedKernel\Application\ServiceLocator;
use Dumplie\SharedKernel\Application\Transaction\Transaction;
use Dumplie\Customer\Domain\CartId;
use Dumplie\Customer\Domain\Exception\ProductNotFoundException;
use Dumplie\Customer\Infrastructure\InMemory\InMemoryCarts;
use Dumplie\Customer\Infrastructure\InMemory\InMemoryProducts;
use Dumplie\SharedKernel\Infrastructure\InMemory\InMemoryServiceLocator;
use Dumplie\SharedKernel\Infrastructure\Tactician\CommandBus;
use Dumplie\SharedKernel\Infrastructure\Tactician\Middleware\ExtensionMiddleware;
use Dumplie\SharedKernel\Tests\Double\Application\Transaction\FactoryStub;
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
