<?php

namespace Bluebadger\JasperPim\Model\Data;

class ProductPricing implements \Bluebadger\JasperPim\Api\Data\ProductPricingInterface
{

    private $price = 0.00;
    private $specialPrice = null;
    private $cost = null;
    private $msrp = null;
    private $specialFromDate = '';
    private $specialToDate = '';
    private $groupPrices = [];

    private $costWasProvided = false;
    private $msrpWasProvided = false;

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    public function getSpecialPrice()
    {
        return $this->specialPrice;
    }

    public function setSpecialPrice($special_price)
    {
        $this->specialPrice = $special_price;
        return $this;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setCost($cost)
    {
        $this->costWasProvided = true;
        $this->cost = $cost;
        return $this;
    }

    public function getMsrp()
    {
        return $this->msrp;
    }

    public function setMsrp($msrp)
    {
        $this->msrpWasProvided = true;
        $this->msrp = $msrp;
        return $this;
    }

    public function getSpecialFromDate()
    {
        return $this->specialFromDate;
    }

    public function setSpecialFromDate($special_from_date)
    {
        $this->specialFromDate = $special_from_date;
        return $this;
    }

    public function getSpecialToDate()
    {
        return $this->specialToDate;
    }

    public function setSpecialToDate($special_to_date)
    {
        $this->specialToDate = $special_to_date;
        return $this;
    }

    public function getGroupPrices()
    {
        return $this->groupPrices;
    }

    public function setGroupPrices($group_prices)
    {
        $this->groupPrices = $group_prices;
        return $this;
    }

    public function getCostWasProvided() {
        return $this->costWasProvided;
    }
    public function getMsrpWasProvided() {
        return $this->msrpWasProvided;
    }
}
