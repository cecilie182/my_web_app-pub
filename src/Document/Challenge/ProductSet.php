<?php

namespace App\Document\Challenge;

use Doctrine\Common\Collections\ArrayCollection;

class ProductSet
{
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function addProduct(Product $product)
    {
        $this->products->add($product);
    }

    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
    }

    public function setProducts(ArrayCollection $products)
    {
        $this->products = $products;
    }

    /**
     * @return ArrayCollection
     */
    public function getProducts(): ArrayCollection
    {
        return $this->products;
    }
}