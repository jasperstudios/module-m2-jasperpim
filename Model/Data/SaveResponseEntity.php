<?php

namespace Bluebadger\JasperPim\Model\Data;

class SaveResponseEntity implements \Bluebadger\JasperPim\Api\Data\SaveResponseEntityInterface {

    private $message = '';
    private $jasper_id = '';
    private $magento_id = 0;
    private $errors = [];

    public function __construct($message, $jasper_id, $magento_id, $errors)
    {
        $this->message = $message;
        $this->jasper_id = $jasper_id;
        $this->magento_id = $magento_id;
        foreach($errors as $error) {
            $this->errors[] = $error->getMessage() . ' (' . $error->getCode() . ')';
        }
    }

    public function getMessage() {
        return $this->message;
    }
    public function getJasperId() {
        return $this->jasper_id;
    }
    public function getMagentoId() {
        return $this->magento_id;
    }
    public function getErrors() {
        return $this->errors;
    }
}