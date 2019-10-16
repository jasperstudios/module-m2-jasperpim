<?php

namespace Bluebadger\JasperPim\Api\Data;

interface ProductInterface extends EntityInterface
{

    /**
     * @return string
     */
    public function getType();
    /**
     * @param string
     * @return $this
     */
    public function setType($type);
    /**
     * @return string
     */
    public function getSku();
    /**
     * @param string
     * @return $this
     */
    public function setSku($sku);
    /**
     * @return string
     */
    public function getAttributeSet();
    /**
     * @param string
     * @return $this
     */
    public function setAttributeSet($attribute_set);

    /**
     * @return \Bluebadger\JasperPim\Api\Data\AttributeValueInterface[]
     */
    public function getAttributes();
    /**
     * @param \Bluebadger\JasperPim\Api\Data\AttributeValueInterface[]
     * @return $this
     */
    public function setAttributes($attributes);

    /**
     * @return \Bluebadger\JasperPim\Api\Data\ProductImageInterface[]
     */
    public function getImages();
    /**
     * @param \Bluebadger\JasperPim\Api\Data\ProductImageInterface[]
     * @return $this
     */
    public function setImages($images);

    /**
     * @return string[]
     */
    public function getWebsites();
    /**
     * @param string[]
     * @return $this
     */
    public function setWebsites($websites);
    /**
     * @return \Bluebadger\JasperPim\Api\Data\ProductPricingInterface
     */
    public function getPricing();
    /**
     * @param \Bluebadger\JasperPim\Api\Data\ProductPricingInterface
     * @return $this
     */
    public function setPricing($pricing);
    /**
     * @return \Bluebadger\JasperPim\Api\Data\ProductInventoryInterface
     */
    public function getInventory();
    /**
     * @param \Bluebadger\JasperPim\Api\Data\ProductInventoryInterface
     * @return $this
     */
    public function setInventory($inventory);
    /**
     * @return string[]
     */
    public function getCategories();
    /**
     * @param string[]
     * @return $this
     */
    public function setCategories($categories);
}
