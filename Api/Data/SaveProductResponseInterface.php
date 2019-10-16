<?php
namespace Bluebadger\JasperPim\Api\Data;

interface SaveProductResponseInterface
{
    /**
     * @return \Bluebadger\JasperPim\Api\Data\SaveResponseEntityInterface
     */
    public function getProduct();

    /**
     * @param $entity
     * @param $message
     * @return mixed
     */
    public function setProduct($entity, $message);
}