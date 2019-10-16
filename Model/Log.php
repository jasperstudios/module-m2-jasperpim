<?php

namespace Bluebadger\JasperPim\Model;

class Log extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(ResourceModel\Log::class);

        $this->setData([
            'time' => date('Y-m-d H:i:s'),
            'pid' => getmypid()
        ]);
    }
}
