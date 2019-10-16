<?php
namespace Bluebadger\JasperPim\Api\Data;

interface SaveAttributeOptionResponseInterface
{
    /**
     * @return \Bluebadger\JasperPim\Api\Data\SaveResponseEntityInterface
     */
    public function getAttributeOption();

    /**
     * @param $entity
     * @param $message
     * @return mixed
     */
    public function setAttributeOption($entity, $message);
}