<?php

namespace Bluebadger\JasperPim\Model;

class Logger
{

    const XML_PATH_DEBUG = 'jasperpim/general/debug';
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Bluebadger\JasperPim\Model\LogFactory
     */
    private $logFactory;

    /**
     * @var bool
     */
    private $_debugEnabled = null;

    /**
     * @var string
     */
    private $instanceId;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Bluebadger\JasperPim\Model\LogFactory $logFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->logFactory = $logFactory;

        $this->instanceId = substr(microtime(true)/100, -5) . getmypid();
    }

    private function isDebugEnabled()
    {
        if ($this->_debugEnabled === null) {
            $this->_debugEnabled = (bool) $this->scopeConfig->getValue(self::XML_PATH_DEBUG);
        }
        return $this->_debugEnabled;
    }

    public function error($message)
    {
        $this->log($message, 'error');
    }

    public function debug($message)
    {
        if ($this->isDebugEnabled()) {
            $this->log($message, 'debug');
        }
    }

    protected function log($message, $level)
    {
        $log = $this->logFactory->create();
        $log->setLevel($level)->setMessage($message)->setInstanceId($this->instanceId);
        return $log->save();
    }
}
