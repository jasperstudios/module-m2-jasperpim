<?php
namespace Bluebadger\JasperPim\Api\Data;

interface ResponseEntityInterface
{
    /**
     * @return string
     */
    public function getMessage();
    /**
     * @return int
     */
    public function getMagentoId();
    /**
     * @return string[]
     */
    public function getErrors();
}
