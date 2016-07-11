<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Infrastructure\Doctrine\ORM\Domain;

use Doctrine\ORM\EntityManager;
use Dumplie\Customer\Domain\CartId;
use Dumplie\Customer\Domain\Checkout;
use Dumplie\Customer\Domain\Checkouts;
use Dumplie\Customer\Domain\Exception\CheckoutNotFoundException;

final class ORMCheckouts implements Checkouts
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Checkout $checkout
     */
    public function add(Checkout $checkout)
    {
        $this->entityManager->persist($checkout);
    }

    /**
     * @param CartId $cartId
     * @return bool
     */
    public function existsForCart(CartId $cartId) : bool
    {
        $checkout = $this->entityManager->getRepository(Checkout::class)->findOneBy(['cartId.id' => $cartId]);

        return !is_null($checkout);
    }

    /**
     * @param CartId $cartId
     * @return Checkout
     * @throws CheckoutNotFoundException
     */
    public function getForCart(CartId $cartId) : Checkout
    {
        $checkout = $this->entityManager->getRepository(Checkout::class)->findOneBy(['cartId.id' => $cartId]);

        if (is_null($checkout)){
            throw CheckoutNotFoundException::byCartId($cartId);
        }

        return $checkout;
    }

    /**
     * @param CartId $cartId
     * @throws CheckoutNotFoundException
     */
    public function removeForCart(CartId $cartId)
    {
        $checkout = $this->getForCart($cartId);

        $this->entityManager->remove($checkout);
    }
}