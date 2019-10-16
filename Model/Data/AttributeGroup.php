<?php

namespace Bluebadger\JasperPim\Model\Data;

use Bluebadger\JasperPim\Api\Data\AttributeGroupInterface;

class AttributeGroup implements \Bluebadger\JasperPim\Api\Data\AttributeGroupInterface
{

    private $name = '';
    private $attributes = [];

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }
}
