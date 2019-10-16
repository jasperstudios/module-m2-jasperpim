<?php
namespace Bluebadger\JasperPim\Api\Data;

interface SaveCategoryResponseInterface
{
    /**
     * @return \Bluebadger\JasperPim\Api\Data\SaveResponseEntityInterface
     */
    public function getCategory();

    /**
     * @param $entity
     * @param $message
     * @return mixed
     */
    public function setCategory($entity, $message);
}