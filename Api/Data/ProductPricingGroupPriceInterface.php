<?php

namespace Bluebadger\JasperPim\Api\Data;

interface ProductPricingGroupPriceInterface
{
    /**
     * @return float
     */
    public function getPrice();
    /**
     * @param float
     * @return $this
     */
    public function setPrice($price);
    /**
     * @return int
     */
    public function getPriceQty();
    /**
     * @param int
     * @return $this
     */
    public function setPriceQty($price_qty);
    /**
     * @return string
     */
    public function getWebsite();
    /**
     * @param string
     * @return $this
     */
    public function setWebsite($website);
    /**
     * @return string
     */
    public function getCustomerGroup();
    /**
     * @param string
     * @return $this
     */
    public function setCustomerGroup($customer_group);

    /**
     * @return string
     */
    public function getValueType();

    /**
     * @param string $value_type
     * @return $this
     */
    public function setValueType($value_type);
}
