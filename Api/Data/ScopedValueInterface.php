<?php

namespace Bluebadger\JasperPim\Api\Data;

interface ScopedValueInterface
{
    /**
     * @return string
     */
    public function getScope();
    /**
     * @param string $scope
     * @return $this
     */
    public function setScope($scope);
    /**
     * @return mixed
     */
    public function getValue();
    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value);
}
