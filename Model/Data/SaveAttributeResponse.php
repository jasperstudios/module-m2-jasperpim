<?php

namespace Bluebadger\JasperPim\Model\Data;

class SaveAttributeResponse extends SaveEntityResponse implements \Bluebadger\JasperPim\Api\Data\SaveAttributeResponseInterface {
    public function setAttribute($entity, $message)
    {
        $this->setEntity($entity, $message);
    }

    /**
     * @return SaveResponseEntity
     */
    public function getAttribute() {
        return $this->getEntity();
    }
}