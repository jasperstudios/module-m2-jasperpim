<?php

namespace Bluebadger\JasperPim\Model\Data;

class ResponseEntity implements \Bluebadger\JasperPim\Api\Data\ResponseEntityInterface
{

    private $message = '';
    private $magento_id = 0;
    private $errors = [];

    public function __construct($message, $magento_id, $errors)
    {
        $this->message = $message;
        $this->magento_id = $magento_id;
        foreach ($errors as $error) {
            $this->errors[] = $error->getMessage() . ' (' . $error->getCode() . ')';
        }
    }

    public function getMessage()
    {
        return $this->message;
    }
    public function getMagentoId()
    {
        return $this->magento_id;
    }
    public function getErrors()
    {
        return $this->errors;
    }
}
