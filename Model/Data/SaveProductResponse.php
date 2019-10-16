<?php

namespace Bluebadger\JasperPim\Model\Data;

class SaveProductResponse extends SaveEntityResponse implements \Bluebadger\JasperPim\Api\Data\SaveProductResponseInterface {
    public function setProduct($entity, $message)
    {
        $this->setEntity($entity, $message);
    }

    /**
     * @return SaveResponseEntity
     */
    public function getProduct() {
        return $this->getEntity();
    }
}