<?php
namespace Bluebadger\JasperPim\Api\Data;

interface SaveResponseEntityInterface
{
    /**
     * @return string
     */
    public function getMessage();
    /**
     * @return string
     */
    public function getJasperId();
    /**
     * @return int
     */
    public function getMagentoId();
    /**
     * @return string[]
     */
    public function getErrors();
}