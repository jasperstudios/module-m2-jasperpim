<?php
namespace Bluebadger\JasperPim\Api;

/**
 * Interface CategoryInterface
 * @package Bluebadger\JasperPim\Api
 * @api
 */
interface CategoryInterface
{
    /**
     * @param \Bluebadger\JasperPim\Api\Data\CategoryInterface $category
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function post($category);
    /**
     * @param string $magento_id
     * @param \Bluebadger\JasperPim\Api\Data\CategoryInterface $category
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function put($magento_id, $category);
    /**
     * @param string $magento_id
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
     */
    public function delete($magento_id);
}
