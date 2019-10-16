<?php

namespace Bluebadger\JasperPim\Model\Data;

class SaveAttributeOptionResponse extends SaveEntityResponse implements \Bluebadger\JasperPim\Api\Data\SaveAttributeOptionResponseInterface {
    public function setAttributeOption($entity, $message)
    {
        $this->setEntity($entity, $message);
    }

    /**
     * @return SaveResponseEntity
     */
    public function getAttributeOption() {
        return $this->getEntity();
    }
}