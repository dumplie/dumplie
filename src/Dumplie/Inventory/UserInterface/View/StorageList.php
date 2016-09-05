<?php

declare (strict_types = 1);

namespace Dumplie\Inventory\UserInterface\View;

use Dumplie\Inventory\Application\Query\Result\Product;

class StorageList extends AbstractView
{
    /**
     * @var array
     */
    private $products;

    /**
     * @param array $products
     */
    public function __construct(array $products = [])
    {
        $this->products = [];

        foreach ($products as $product) {
            $this->addProduct($product);
        }
    }

    /**
     * @param Product $product
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;
    }

    /**
     * @return Product[]
     */
    public function products() : array
    {
        return $this->products;
    }
}