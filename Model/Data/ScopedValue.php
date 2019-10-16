<?php

namespace Bluebadger\JasperPim\Model\Data;

use Magento\Framework\Phrase;

class ScopedValue implements \Bluebadger\JasperPim\Api\Data\ScopedValueInterface
{
    /**
     * @var string $scope
     */
    private $scope;
    /**
     * @var mixed $value
     */
    private $value;

    private $_scopes = [];

    public function __construct(\Magento\Store\Model\StoreManager $storeManager)
    {
        $stores = $storeManager->getStores();
        $this->_scopes['default'] = 0;
        foreach ($stores as $store) {
            $this->_scopes[$store->getCode()] = $store->getId();
        }
    }

    public function getScope()
    {
        return $this->scope;
    }
    public function setScope($scope)
    {
        $this->validateScope($scope);
        $this->scope = $scope;
    }
    public function getValue()
    {
        return $this->value;
    }
    public function setValue($value)
    {
        $this->value = $value;
    }

    protected function validateScope($scope)
    {
        if (!isset($this->_scopes[$scope])) {
            $cleanScope = substr($scope, 0, 100);
            throw new \Bluebadger\JasperPim\Model\ValidationException("Invalid scope: $cleanScope");
        }
    }
}
