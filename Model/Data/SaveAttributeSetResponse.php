<?php

namespace Bluebadger\JasperPim\Model\Data;

class SaveAttributeSetResponse extends SaveEntityResponse implements \Bluebadger\JasperPim\Api\Data\SaveAttributeSetResponseInterface {
    public function setAttributeSet($entity, $message)
    {
        $this->setEntity($entity, $message);
    }

    /**
     * @return SaveResponseEntity
     */
    public function getAttributeSet() {
        return $this->getEntity();
    }
}