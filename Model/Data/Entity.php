<?php

namespace Bluebadger\JasperPim\Model\Data;

abstract class Entity implements \Bluebadger\JasperPim\Api\Data\EntityInterface
{

    /**
     * @var array
     */
    private $exceptions = [];
    /**
     * @var int
     */
    private $magentoId = 0;
    /**
     * @var \Bluebadger\JasperPim\Helper\Data
     */
    protected $helper;
    /**
     * @var \Bluebadger\JasperPim\Model\Logger
     */
    protected $logger;

    public function __construct(\Bluebadger\JasperPim\Helper\Data $helper, \Bluebadger\JasperPim\Model\Logger $logger)
    {
        $this->helper = $helper;
        $this->logger = $logger;
    }

    /**
     * @return int
     */
    public function getMagentoId()
    {
        return $this->magentoId;
    }

    public function setMagentoId($magento_id)
    {
        $this->magentoId = $magento_id;
    }

    abstract protected function _validate();
    public function validate()
    {
        $this->_validate();
        if ($this->getExceptions()) {
            $exceptions = $this->getExceptions();
            $this->logger->error('Validation failed with ' .
                count($exceptions) .
                ' exception(s), The first one is "' .
                $exceptions[0]->getMessage() .
                '"');
            throw $exceptions[0];
        }
        return true;
    }
    abstract public function save();

    public function addValidationException($message, $code = 0)
    {
        $phrase = $this->helper->createPhrase($message);
        $this->exceptions[] = new \Bluebadger\JasperPim\Model\Exception($phrase, $code, 400);
    }

    public function getExceptions()
    {
        return $this->exceptions;
    }
}
