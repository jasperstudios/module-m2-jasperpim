<?php

namespace Bluebadger\JasperPim\Model\Data;

class ProductConfigurations implements \Bluebadger\JasperPim\Api\Data\ProductConfigurationsInterface
{
    private $superAttributes = [];
    private $associatedProducts = [];

    public function getSuperAttributes()
    {
        return $this->superAttributes;
    }

    public function setSuperAttributes($super_attributes)
    {
        $this->superAttributes = $super_attributes;
        return $this;
    }

    public function getAssociatedProducts()
    {
        return $this->associatedProducts;
    }

    public function setAssociatedProducts($associated_products)
    {
        $this->associatedProducts = $associated_products;
        return $this;
    }
}
