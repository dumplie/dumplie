<?php

declare (strict_types = 1);

namespace Dumplie\Domain\Customer;

final class Order
{
    /**
     * @var OrderId
     */
    private $id;

    /**
     * @var array
     */
    private $orderItems;

    /**
     * @var Address
     */
    private $billing;

    /**
     * @var Address
     */
    private $shipping;

    /**
     * @param OrderId $id
     * @param array $orderItems
     * @param Address $billing
     * @param Address $shipping
     */
    public function __construct(OrderId $id, array $orderItems = [], Address $billing, Address $shipping)
    {
        $this->id = $id;
        $this->orderItems = [];
        $this->billing = $billing;
        $this->shipping = $shipping;

        foreach ($orderItems as $item) {
            $this->addItem($item);
        }
    }

    /**
     * @return OrderId
     */
    public function id() : OrderId
    {
        return $this->id;
    }


    /**
     * @param OrderItem $item
     */
    private function addItem(OrderItem $item)
    {
        $this->orderItems[(string) $item->sku()] = $item;
    }
}