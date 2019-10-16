<?php
namespace Bluebadger\JasperPim\Api\Data;

interface SaveAttributeSetResponseInterface
{
    /**
     * @return \Bluebadger\JasperPim\Api\Data\SaveResponseEntityInterface
     */
    public function getAttributeSet();

    /**
     * @param $entity
     * @param $message
     * @return mixed
     */
    public function setAttributeSet($entity, $message);
}