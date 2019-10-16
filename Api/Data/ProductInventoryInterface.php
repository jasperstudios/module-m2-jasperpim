<?php

namespace Bluebadger\JasperPim\Api\Data;

interface ProductInventoryInterface
{
    /**
     * @return int
     */
    public function getQty();
    /**
     * @param int
     * @return $this
     */
    public function setQty($qty);
    /**
     * @return bool
     */
    public function getIsInStock();
    /**
     * @param bool
     * @return $this
     */
    public function setIsInStock($is_in_stock);
}
