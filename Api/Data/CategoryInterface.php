<?php

namespace Bluebadger\JasperPim\Api\Data;

interface CategoryInterface extends EntityInterface
{
    /**
     * @return bool
     */
    public function getIsActive();
    /**
     * @param bool
     * @return $this
     */
    public function setIsActive($is_active);
    /**
     * @return bool
     */
    public function getIncludeInMenu();
    /**
     * @param bool
     * @return $this
     */
    public function setIncludeInMenu($include_in_menu);
    /**
     * @return bool
     */
    public function getIsAnchor();
    /**
     * @param bool
     * @return $this
     */
    public function setIsAnchor($is_anchor);
    /**
     * @return int
     */
    public function getPosition();
    /**
     * @param int
     * @return $this
     */
    public function setPosition($position);
    /**
     * @return \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     */
    public function getName();
    /**
     * @param \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     * @return $this
     */
    public function setName($name);
    /**
     * @return \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     */
    public function getDescription();
    /**
     * @param \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     * @return $this
     */
    public function setDescription($description);
    /**
     * @return \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     */
    public function getImage();
    /**
     * @param \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     * @return $this
     */
    public function setImage($image);
    /**
     * @return \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     */
    public function getMetaTitle();
    /**
     * @param \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     * @return $this
     */
    public function setMetaTitle($meta_title);
    /**
     * @return \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     */
    public function getMetaKeywords();
    /**
     * @param \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     * @return $this
     */
    public function setMetaKeywords($meta_keywords);
    /**
     * @return \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     */
    public function getMetaDescription();
    /**
     * @param \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     * @return $this
     */
    public function setMetaDescription($meta_description);
    /**
     * @return \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     */
    public function getUrlKey();
    /**
     * @param \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     * @return $this
     */
    public function setUrlKey($url_key);
    /**
     * @return string
     */
    public function getParent();
    /**
     * @param int
     * @return $this
     */
    public function setParent($parent);
}
