<?php

namespace Bluebadger\JasperPim\Model;

class AttributeOptionRepository extends AbstractRepository implements \Bluebadger\JasperPim\Api\AttributeOptionInterface
{
    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    private $connection;

    /**
     * @var \Bluebadger\JasperPim\Api\Data\ResponseEntityInterfaceFactory
     */
    private $saveResponseFactory;
    /**
     * @var \Bluebadger\JasperPim\Api\BatchInterface
     */
    private $batchRepository;

    /**
     * @var \Bluebadger\JasperPim\Api\Data\AttributeOptionInterfaceFactory
     */
    private $attributeOptionFactory;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Bluebadger\JasperPim\Api\Data\ResponseEntityInterfaceFactory $saveResponseFactory,
        \Bluebadger\JasperPim\Api\BatchInterface $batchRepository,
        \Bluebadger\JasperPim\Api\Data\AttributeOptionInterfaceFactory $attributeOptionFactory,
        \Bluebadger\JasperPim\Helper\Config $configHelper,
        Logger $logger
    ) {

        parent::__construct($configHelper);

        $this->connection = $resource->getConnection();
        $this->saveResponseFactory = $saveResponseFactory;
        $this->batchRepository = $batchRepository;
        $this->attributeOptionFactory = $attributeOptionFactory;
        $this->logger = $logger;
    }

    public function post($attribute_option)
    {
        $this->logger->debug('AttributeOption::Post was called');
        $response = ($this->batchRepository->post([], [$attribute_option], [], [], [])->getAttributeOptions())[0];
        $this->logger->debug('AttributeOption::Post success');
        return $response;
    }

    public function put($magento_id, $attribute_option)
    {
        $this->logger->debug('AttributeOption::Put was called');
        $attribute_option->setMagentoId($magento_id);
        $response = ($this->batchRepository->post([], [$attribute_option], [], [], [])->getAttributeOptions())[0];
        $this->logger->debug('AttributeOption::Put success');
        return $response;
    }

    public function delete($magento_id)
    {
        $attribute_option = $this->attributeOptionFactory->create();
        $attribute_option->setMagentoId($magento_id);
        if ($attribute_option->delete()) {
            return true;
        } else {
            return false;
        }
    }
}
