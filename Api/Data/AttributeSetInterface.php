<?php

namespace Bluebadger\JasperPim\Api\Data;

interface AttributeSetInterface extends EntityInterface
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
     * @return \Bluebadger\JasperPim\Api\Data\AttributeGroupInterface[]
     */
    public function getGroups();

    /**
     * @param \Bluebadger\JasperPim\Api\Data\AttributeGroupInterface[]
     * @return $this
     */
    public function setGroups($groups);
}
