<?php
namespace Bluebadger\JasperPim\Api;

/**
 * Interface AttributeSetInterface
 * @package Bluebadger\JasperPim\Api
 * @api
 */
interface AttributeSetInterface
{
    /**
     * @param \Bluebadger\JasperPim\Api\Data\AttributeSetInterface $attribute_set
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function post($attribute_set);
    /**
     * @param string $magento_id
     * @param \Bluebadger\JasperPim\Api\Data\AttributeSetInterface $attribute_set
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function put($magento_id, $attribute_set);
    /**
     * @param string $magento_id
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function delete($magento_id);
}
