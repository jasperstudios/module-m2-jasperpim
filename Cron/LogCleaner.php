<?php

namespace Bluebadger\JasperPim\Cron;

class LogCleaner
{
    /**
     * @var \Bluebadger\JasperPim\Model\ResourceModel\Log\CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var \Bluebadger\JasperPim\Helper\Config
     */
    private $configHelper;
    /**
     * @var \Bluebadger\JasperPim\Helper\Data
     */
    private $helper;

    public function __construct(
        \Bluebadger\JasperPim\Model\ResourceModel\Log\CollectionFactory $collectionFactory,
        \Bluebadger\JasperPim\Helper\Config $configHelper,
        \Bluebadger\JasperPim\Helper\Data $helper
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->configHelper = $configHelper;
        $this->helper = $helper;
    }
    public function execute()
    {
        $days = $this->configHelper->getLogRetention();
        if ($days > 0) {
            $this->helper->getConnection()->delete(
                $this->helper->getTableName('jasperpim_log'),
                "(time + 0) < (NOW() - {$days}000000)"
            );
        }
    }
}
