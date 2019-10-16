<?php

namespace Bluebadger\JasperPim\Api\Data;

interface AttributeFrontendInterface
{
    /**
     * @return bool
     */
    public function getIsSearchable();
    /**
     * @param bool $is_searchable
     * @return $this
     */
    public function setIsSearchable($is_searchable);
    /**
     * @return bool
     */
    public function getIsComparable();
    /**
     * @param bool $is_comparable
     * @return $this
     */
    public function setIsComparable($is_comparable);
    /**
     * @return bool
     */
    public function getIsFilterable();
    /**
     * @param bool $is_filterable
     * @return $this
     */
    public function setIsFilterable($is_filterable);
    /**
     * @return bool
     */
    public function getIsFilterableInSearch();
    /**
     * @param bool $is_filterable_in_search
     * @return $this
     */
    public function setIsFilterableInSearch($is_filterable_in_search);
    /**
     * @return string
     */
    public function getLayeredNavigationCanonical();
    /**
     * @param string $layered_navigation_canonical
     * @return $this
     */
    public function setLayeredNavigationCanonical($layered_navigation_canonical);
    /**
     * @return int
     */
    public function getPosition();
    /**
     * @param int $position
     * @return $this
     */
    public function setPosition($position);
    /**
     * @return bool
     */
    public function getIsUsedForPromoRules();
    /**
     * @param bool $is_used_for_promo_rules
     * @return $this
     */
    public function setIsUsedForPromoRules($is_used_for_promo_rules);
    /**
     * @return bool
     */
    public function getIsHtmlAllowedOnFront();
    /**
     * @param bool $is_html_allowed_on_front
     * @return $this
     */
    public function setIsHtmlAllowedOnFront($is_html_allowed_on_front);
    /**
     * @return bool
     */
    public function getIsVisibleOnFront();
    /**
     * @param bool $is_visible_on_front
     * @return $this
     */
    public function setIsVisibleOnFront($is_visible_on_front);
    /**
     * @return bool
     */
    public function getUsedInProductListing();
    /**
     * @param bool $used_in_product_listing
     * @return $this
     */
    public function setUsedInProductListing($used_in_product_listing);
    /**
     * @return bool
     */
    public function getUsedForSortBy();

    /**
     * @param bool $used_for_sort_by
     * @return $this
     */
    public function setUsedForSortBy($used_for_sort_by);
    /**
     * @return bool
     */
    public function getIsVisibleInAdvancedSearch();
    /**
     * @param bool $is_visible_in_advanced_search
     * @return $this
     */
    public function setIsVisibleInAdvancedSearch($is_visible_in_advanced_search);
}
