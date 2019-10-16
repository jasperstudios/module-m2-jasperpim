<?php
namespace Bluebadger\JasperPim\Api;

/**
 * Interface BatchInterface
 * @package Bluebadger\JasperPim\Api
 * @api
 */
interface BatchInterface
{
    /**
     * @param \Bluebadger\JasperPim\Api\Data\AttributeInterface[] $attributes
     * @param \Bluebadger\JasperPim\Api\Data\AttributeOptionInterface[] $attribute_options
     * @param \Bluebadger\JasperPim\Api\Data\AttributeSetInterface[] $attribute_sets
     * @param \Bluebadger\JasperPim\Api\Data\CategoryInterface[] $categories
     * @param \Bluebadger\JasperPim\Api\Data\ProductInterface[] $products
     * @return \Bluebadger\JasperPim\Api\Data\SaveResponseInterface
     */
    public function post(
        $attributes = [],
        $attribute_options = [],
        $attribute_sets = [],
        $categories = [],
        $products = []
    );
}
