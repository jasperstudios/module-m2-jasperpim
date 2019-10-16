<?php
namespace Bluebadger\JasperPim\Api\Data;

interface SaveResponseInterface
{
    /**
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface[]
     */
    public function getAttributes();
    /**
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface[]
     */
    public function getAttributeOptions();
    /**
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface[]
     */
    public function getAttributeSets();
    /**
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface[]
     */
    public function getCategories();
    /**
     * @return \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface[]
     */
    public function getProducts();
}
