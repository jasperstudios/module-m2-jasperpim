<?php
namespace Bluebadger\JasperPim\Api;

/**
 * Interface AttributeInterface
 * @package Bluebadger\JasperPim\Api
 * @api
 */
interface AttributeInterface
{
    /**
     * @param \Bluebadger\JasperPim\Api\Data\AttributeInterface $attribute
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function post($attribute);
    /**
     * @param string $magento_id
     * @param \Bluebadger\JasperPim\Api\Data\AttributeInterface $attribute
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function put($magento_id, $attribute);
    /**
     * @param string $magento_id
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function delete($magento_id);
}
