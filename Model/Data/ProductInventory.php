<?php

namespace Bluebadger\JasperPim\Model\Data;

use Bluebadger\JasperPim\Api\Data\ProductInventoryInterface;

class ProductInventory implements \Bluebadger\JasperPim\Api\Data\ProductInventoryInterface
{
    private $qty = 0;
    private $isInStock = false;
    public function getQty()
    {
        return $this->qty;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
        return $this;
    }

    public function getIsInStock()
    {
        return $this->isInStock;
    }

    public function setIsInStock($is_in_stock)
    {
        $this->isInStock = $is_in_stock;
        return $this;
    }
}
