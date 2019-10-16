<?php

namespace Bluebadger\JasperPim\Api\Data;

interface AttributeInterface extends EntityInterface
{

    /**
     * @return string
     */
    public function getAttributeCode();

    /**
     * @param string $attribute_code
     * @return $this
     */
    public function setAttributeCode($attribute_code);

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
     * @return string
     */
    public function getInputType();
    /**
     * @param string $input_type
     * @return $this
     */
    public function setInputType($input_type);

    /**
     * @return bool
     */
    public function getRequired();

    /**
     * @param bool $required
     * @return $this
     */
    public function setRequired($required);

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
     * @return bool
     */
    public function getUnique();

    /**
     * @param bool $unique
     * @return $this
     */
    public function setUnique($unique);

    /**
     * @return string
     */
    public function getValidation();

    /**
     * @param string $validation
     * @return $this
     */
    public function setValidation($validation);

    /**
     * @return bool
     */
    public function getIsUsedInGrid();

    /**
     * @param bool $is_used_in_grid
     * @return $this
     */
    public function setIsUsedInGrid($is_used_in_grid);

    /**
     * @return bool
     */
    public function getIsFilterableInGrid();

    /**
     * @param bool $is_filterable_in_grid
     * @return $this
     */
    public function setIsFilterableInGrid($is_filterable_in_grid);
    /**
     * @return \Bluebadger\JasperPim\Api\Data\AttributeFrontendInterface
     */
    public function getFrontend();
    /**
     * @param \Bluebadger\JasperPim\Api\Data\AttributeFrontendInterface $frontend
     * @return $this
     */
    public function setFrontend($frontend);
}
