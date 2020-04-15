<?php

namespace Bluebadger\JasperPim\Api\Data;

interface ProductPricingInterface
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
     * @return float
     */
    public function getSpecialPrice();
    /**
     * @param float
     * @return $this
     */
    public function setSpecialPrice($special_price);
    /**
     * @return float
     */
    public function getCost();
    /**
     * @param float
     * @return $this
     */
    public function setCost($cost);
    /**
     * @return float
     */
    public function getMsrp();
    /**
     * @param float
     * @return $this
     */
    public function setMsrp($msrp);

    /**
     * @return string
     */
    public function getSpecialFromDate();

    /**
     * @param string $special_from_date
     * @return $this
     */
    public function setSpecialFromDate($special_from_date);
    /**
     * @return string
     */
    public function getSpecialToDate();
    /**
     * @param string $special_to_date
     * @return $this
     */
    public function setSpecialToDate($special_to_date);

    /**
     * @return \Bluebadger\JasperPim\Api\Data\ProductPricingGroupPriceInterface[]
     */
    public function getGroupPrices();

    /**
     * @param \Bluebadger\JasperPim\Api\Data\ProductPricingGroupPriceInterface[]$group_prices
     * @return $this
     */
    public function setGroupPrices($group_prices);

    /**
     * @return bool
     */
    public function getCostWasProvided();
    /**
     * @return bool
     */
    public function getMsrpWasProvided();
}
