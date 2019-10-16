<?php
namespace Bluebadger\JasperPim\Api;

/**
 * Interface AttributeOptionInterface
 * @package Bluebadger\JasperPim\Api
 * @api
 */
interface AttributeOptionInterface
{
    /**
     * @param \Bluebadger\JasperPim\Api\Data\AttributeOptionInterface $attribute_option
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function post($attribute_option);
    /**
     * @param string $magento_id
     * @param \Bluebadger\JasperPim\Api\Data\AttributeOptionInterface $attribute_option
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function put($magento_id, $attribute_option);
    /**
     * @param string $magento_id
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function delete($magento_id);
}
