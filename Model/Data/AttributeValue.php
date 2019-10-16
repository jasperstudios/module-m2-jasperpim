<?php

namespace Bluebadger\JasperPim\Model\Data;

class AttributeValue implements \Bluebadger\JasperPim\Api\Data\AttributeValueInterface
{
    private $code;
    private $value;

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}
