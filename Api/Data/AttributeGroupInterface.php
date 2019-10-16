<?php

namespace Bluebadger\JasperPim\Api\Data;

interface AttributeGroupInterface
{
    /**
     * @return string
     */
    public function getName();
    /**
     * @param string
     * @return $this
     */
    public function setName($name);

    /**
     * @return string[]
     */
    public function getAttributes();

    /**
     * @param string[]
     * @return $this
     */
    public function setAttributes($attributes);
}
