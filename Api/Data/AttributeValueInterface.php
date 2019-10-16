<?php

namespace Bluebadger\JasperPim\Api\Data;

interface AttributeValueInterface
{
    /**
     * @return string
     */
    public function getCode();
    /**
     * @param string
     * @return $this
     */
    public function setCode($code);

    /**
     * @return \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     */
    public function getValue();

    /**
     * @param \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     * @return $this
     */
    public function setValue($value);
}
