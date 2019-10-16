<?php

namespace Bluebadger\JasperPim\Api\Data;

interface AttributeOptionInterface extends EntityInterface
{
    /**
     * @return string
     */
    public function getAttribute();

    /**
     * @param string
     * @return $this
     */
    public function setAttribute($attribute);

    /**
     * @return \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     */
    public function getLabels();

    /**
     * @param \Bluebadger\JasperPim\Api\Data\ScopedValueInterface[]
     * @return $this
     */
    public function setLabels($labels);

    /**
     * @return bool
     */
    public function getIsDefault();
    /**
     * @param bool $is_default
     * @return $this
     */
    public function setIsDefault($is_default);

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @param int
     * @return $this
     */
    public function setPosition($position);
}
