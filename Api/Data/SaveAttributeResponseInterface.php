<?php
namespace Bluebadger\JasperPim\Api\Data;

interface SaveAttributeResponseInterface
{
    /**
     * @return \Bluebadger\JasperPim\Api\Data\SaveResponseEntityInterface
     */
    public function getAttribute();

    /**
     * @param $entity
     * @param $message
     * @return mixed
     */
    public function setAttribute($entity, $message);
}