<?php

namespace Bluebadger\JasperPim\Api\Data;

interface EntityInterface
{
    /**
     * @return int
     */
    public function getMagentoId();
    /**
     * @param string $magento_id
     * @return $this
     */
    public function setMagentoId($magento_id);

    /**
     * @return mixed
     */
    public function validate();
    /**
     * @return mixed
     */
    public function save();
    /**
     * @return mixed
     */
    public function addValidationException($message, $code);
    /**
     * @return mixed
     */
    public function getExceptions();
}
