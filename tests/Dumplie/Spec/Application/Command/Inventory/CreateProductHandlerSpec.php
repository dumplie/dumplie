<?php

namespace Spec\Dumplie\Application\Command\Inventory;

use Dumplie\Application\Command\Inventory\CreateProduct;
use Dumplie\Application\Transaction\Factory as TransactionFactory;
use Dumplie\Application\Transaction\Transaction;
use Dumplie\Domain\Inventory\Product;
use Dumplie\Domain\Inventory\Products;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CreateProductHandlerSpec extends ObjectBehavior
{
    function let(Products $products, TransactionFactory $factory)
    {
        $this->beConstructedWith($products, $factory);
    }
    
    function it_adds_newly_created_product_into_persisted_collection(
        Products $products,
        TransactionFactory $factory,
        Transaction $transaction
    ) {
        $factory->open()->willReturn($transaction);
        $products
            ->add(new Product(
                new SKU("DUMPLIE_SKU_1"),
                Price::fromInt(250, 'PLN'),
                $isInStock = true
            ))
            ->shouldBeCalled()
        ;
        $transaction->commit()->willReturn();
        
        $this->handle(new CreateProduct(
            "DUMPLIE_SKU_1",
            250,
            'PLN',
            true
        ));
    }

    function it_commits_opened_transaction_after_adding_product(
        Products $products,
        TransactionFactory $factory,
        Transaction $transaction
    ) {
        $factory->open()->willReturn($transaction);
        $products
            ->add(new Product(
                new SKU("DUMPLIE_SKU_1"),
                Price::fromInt(250, 'PLN'),
                $isInStock = true
            ))
            ->willReturn()
        ;
        $transaction->commit()->shouldBeCalled();

        $this->handle(new CreateProduct(
            "DUMPLIE_SKU_1",
            250,
            'PLN',
            true
        ));
    }
    
    function it_rollback_opened_transaction_when_exception_occur_during_adding_product(
        Products $products,
        TransactionFactory $factory,
        Transaction $transaction
    ) {
        $factory->open()->willReturn($transaction);
        $products
            ->add(new Product(
                new SKU("DUMPLIE_SKU_1"),
                Price::fromInt(250, 'PLN'),
                $isInStock = true
            ))
            ->willThrow(new \Exception('Cannot add product'))
        ;
        $transaction->rollback()->shouldBeCalled();

        $this->shouldThrow(new \Exception('Cannot add product'))->duringHandle(new CreateProduct(
            "DUMPLIE_SKU_1",
            250,
            'PLN',
            true
        ));
    }
}
