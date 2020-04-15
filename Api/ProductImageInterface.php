<?php
namespace Bluebadger\JasperPim\Api;

/**
 * Interface ProductInterface
 * @package Bluebadger\JasperPim\Api
 * @api
 */
interface ProductImageInterface
{
    /**
     * @param \Bluebadger\JasperPim\Api\Data\ProductImageInterface $product_image
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function post($product_image);
    /**
     * @param string $magento_id
     * @param \Bluebadger\JasperPim\Api\Data\ProductImageInterface $product_image
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function put($magento_id, $product_image);
    /**
     * @param string $magento_id
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function delete($magento_id);
}
