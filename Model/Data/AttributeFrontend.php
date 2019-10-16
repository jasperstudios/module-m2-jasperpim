<?php

namespace Bluebadger\JasperPim\Model\Data;

use Bluebadger\JasperPim\Api\Data\AttributeFrontendInterface;

class AttributeFrontend implements AttributeFrontendInterface
{

    private $isSearchable = false;
    private $isComparable = false;
    private $isFilterable = false;
    private $isFilterableInSearch = false;
    private $layeredNavigationCanonical = 'use_config';
    private $position = 0;
    private $isUsedForPromoRules = false;
    private $isHtmlAllowedOnFront = false;
    private $isVisibleOnFront = true;
    private $usedInProductListing = false;
    private $usedForSortBy = false;
    private $isVisibleInAdvancedSearch = false;

    private $availableLayeredNavigationCanonical = ['use_config', 'filtered_page', 'current_category'];

    public function getAvailableLayeredNavigationCanonical()
    {
        return $this->availableLayeredNavigationCanonical;
    }

    public function getIsSearchable()
    {
        return $this->isSearchable;
    }

    public function setIsSearchable($is_searchable)
    {
        $this->isSearchable = $is_searchable;
        return $this;
    }

    public function getIsComparable()
    {
        return $this->isComparable;
    }

    public function setIsComparable($is_comparable)
    {
        $this->isComparable = $is_comparable;
        return $this;
    }

    public function getIsFilterable()
    {
        return $this->isFilterable;
    }

    public function setIsFilterable($is_filterable)
    {
        $this->isFilterable = $is_filterable;
        return $this;
    }

    public function getIsFilterableInSearch()
    {
        return $this->isFilterableInSearch;
    }

    public function setIsFilterableInSearch($is_filterable_in_search)
    {
        $this->isFilterableInSearch = $is_filterable_in_search;
        return $this;
    }

    public function getLayeredNavigationCanonical()
    {
        return $this->layeredNavigationCanonical;
    }

    public function setLayeredNavigationCanonical($layered_navigation_canonical)
    {
        $this->layeredNavigationCanonical = $layered_navigation_canonical;
        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    public function getIsUsedForPromoRules()
    {
        return $this->isUsedForPromoRules;
    }

    public function setIsUsedForPromoRules($is_used_for_promo_rules)
    {
        $this->isUsedForPromoRules = $is_used_for_promo_rules;
        return $this;
    }

    public function getIsHtmlAllowedOnFront()
    {
        return $this->isHtmlAllowedOnFront;
    }

    public function setIsHtmlAllowedOnFront($is_html_allowed_on_front)
    {
        $this->isHtmlAllowedOnFront = $is_html_allowed_on_front;
        return $this;
    }

    public function getIsVisibleOnFront()
    {
        return $this->isVisibleOnFront;
    }

    public function setIsVisibleOnFront($is_visible_on_front)
    {
        $this->isVisibleOnFront = $is_visible_on_front;
        return $this;
    }

    public function getUsedInProductListing()
    {
        return $this->usedInProductListing;
    }

    public function setUsedInProductListing($used_in_product_listing)
    {
        $this->usedInProductListing = $used_in_product_listing;
        return $this;
    }

    public function getUsedForSortBy()
    {
        return $this->usedForSortBy;
    }

    public function setUsedForSortBy($used_for_sort_by)
    {
        $this->usedForSortBy = $used_for_sort_by;
    }

    public function getIsVisibleInAdvancedSearch()
    {
        return $this->isVisibleInAdvancedSearch;
    }

    public function setIsVisibleInAdvancedSearch($is_visible_in_advanced_Search)
    {
        $this->isVisibleInAdvancedSearch = $is_visible_in_advanced_Search;
        return $this;
    }
}
