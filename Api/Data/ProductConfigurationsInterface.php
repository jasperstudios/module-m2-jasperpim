<?php

namespace Bluebadger\JasperPim\Api\Data;

interface ProductConfigurationsInterface
{
    /**
     * @return string[]
     */
    public function getSuperAttributes();
    /**
     * @param string[]
     * @return $this
     */
    public function setSuperAttributes($super_attributes);
    /**
     * @return int[]
     */
    public function getAssociatedProducts();
    /**
     * @param int[]
     * @return $this
     */
    public function setAssociatedProducts($associated_products);
}
