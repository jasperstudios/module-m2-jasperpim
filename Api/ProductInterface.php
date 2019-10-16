<?php
namespace Bluebadger\JasperPim\Api;

/**
 * Interface ProductInterface
 * @package Bluebadger\JasperPim\Api
 * @api
 */
interface ProductInterface
{
    /**
     * @param \Bluebadger\JasperPim\Api\Data\ProductInterface $product
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function post($product);
    /**
     * @param string $magento_id
     * @param \Bluebadger\JasperPim\Api\Data\ProductInterface $product
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function put($magento_id, $product);
    /**
     * @param string $magento_id
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function delete($magento_id);
}
