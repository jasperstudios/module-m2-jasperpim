<?php

namespace Bluebadger\JasperPim\Model\Data;

class ProductPricingGroupPrice implements \Bluebadger\JasperPim\Api\Data\ProductPricingGroupPriceInterface
{

    private $website = '';
    private $customerGroup = '';
    private $price = 0.00;
    private $priceQty = 0;
    private $valueType = 'fixed';

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
        return $this;
    }

    public function getCustomerGroup()
    {
        return $this->customerGroup;
    }

    public function setCustomerGroup($customer_group)
    {
        $this->customerGroup = $customer_group;
        return $this;
    }

    public function getPriceQty()
    {
        return $this->priceQty;
    }

    public function setPriceQty($price_qty)
    {
        $this->priceQty = $price_qty;
        return $this;
    }

    public function getValueType()
    {
        return $this->valueType;
    }

    public function setValueType($value_type)
    {
        $this->valueType = $value_type;
        return $this;
    }
}
