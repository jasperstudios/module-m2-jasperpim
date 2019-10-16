<?php

namespace Bluebadger\JasperPim\Model\Data;

class SaveCategoryResponse extends SaveEntityResponse implements \Bluebadger\JasperPim\Api\Data\SaveCategoryResponseInterface {
    public function setCategory($entity, $message)
    {
        $this->setEntity($entity, $message);
    }

    /**
     * @return SaveResponseEntity
     */
    public function getCategory() {
        return $this->getEntity();
    }
}