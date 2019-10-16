<?php
namespace Bluebadger\JasperPim\Model\ResourceModel\Log;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'log_id';
    protected $_eventPrefix = 'jasperpim_log_collection';
    protected $_eventObject = 'log_collection';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Bluebadger\JasperPim\Model\Log::class, \Bluebadger\JasperPim\Model\ResourceModel\Log::class);
    }
}
