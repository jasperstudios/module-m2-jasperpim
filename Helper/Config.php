<?php

namespace Bluebadger\JasperPim\Helper;

class Config
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function getEnable()
    {
        return (bool) $this->scopeConfig->getValue('jasperpim/general/enable');
    }
    /**
     * @return bool
     */
    public function getDebug()
    {
        return (bool) $this->scopeConfig->getValue('jasperpim/general/debug');
    }
    /**
     * @return int
     */
    public function getLogRetention()
    {
        return (int) $this->scopeConfig->getValue('jasperpim/general/log_retention');
    }

    public function getCategoryIsUsedInProductUrl($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            \Magento\Catalog\Helper\Product::XML_PATH_PRODUCT_URL_USE_CATEGORY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
