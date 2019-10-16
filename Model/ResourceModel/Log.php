<?php
namespace Bluebadger\JasperPim\Model\ResourceModel;

class Log extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('jasperpim_log', 'log_id');
    }
}
