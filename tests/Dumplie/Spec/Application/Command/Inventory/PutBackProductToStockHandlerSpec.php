<?php

namespace Spec\Dumplie\Application\Command\Inventory;

use Dumplie\Application\Command\Inventory\PutBackProductToStock;
use Dumplie\Application\Transaction\Factory;
use Dumplie\Application\Transaction\Transaction;
use Dumplie\Domain\Inventory\Product;
use Dumplie\Domain\Inventory\Products;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PutBackProductToStockHandlerSpec extends ObjectBehavior
{
    function let(Products $products, Factory $factory)
    {
        $this->beConstructedWith($products, $factory);
    }
    
    function it_commits_opened_transaction_when_succesfully_put_back_product_from_stock(
        Products $products,
        Factory $factory,
        Transaction $transaction
    ) {
        $factory->open()->willReturn($transaction);

        $product = new Product(
            new SKU('DUMPLIE-SKU-1'),
            Price::fromInt(20, 'EUR'),
            false
        );
        $products
            ->getBySku(new SKU('DUMPLIE-SKU-1'))
            ->willReturn($product)
        ;
       
        $transaction->commit()->shouldBeCalled();
        
        $this->handle(new PutBackProductToStock('DUMPLIE-SKU-1'));
    }
    
    function it_rollback_transaction_when_exception_occur_during_commiting(
        Products $products,
        Factory $factory,
        Transaction $transaction
    ) {
        $factory->open()->willReturn($transaction);

        $product = new Product(
            new SKU('DUMPLIE-SKU-1'),
            Price::fromInt(20, 'EUR'),
            false
        );
        $products
            ->getBySku(new SKU('DUMPLIE-SKU-1'))
            ->willReturn($product)
        ;

        $transaction->commit()->willThrow(new \Exception('Error'));
        $transaction->rollback()->shouldBeCalled();

        $this
            ->shouldThrow(new \Exception('Error'))
            ->duringHandle(new PutBackProductToStock('DUMPLIE-SKU-1'))
        ;
    }
}
