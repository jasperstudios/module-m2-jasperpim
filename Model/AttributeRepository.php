<?php

namespace Bluebadger\JasperPim\Model;

class AttributeRepository extends AbstractRepository implements \Bluebadger\JasperPim\Api\AttributeInterface
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
     * @var \Bluebadger\JasperPim\Api\Data\AttributeInterfaceFactory
     */
    private $attributeFactory;
    /**
     * @var \Bluebadger\JasperPim\Model\Logger
     */
    private $logger;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Bluebadger\JasperPim\Api\Data\ResponseEntityInterfaceFactory $saveResponseFactory,
        \Bluebadger\JasperPim\Api\BatchInterface $batchRepository,
        \Bluebadger\JasperPim\Api\Data\AttributeInterfaceFactory $attributeFactory,
        \Bluebadger\JasperPim\Model\Logger $logger,
        \Bluebadger\JasperPim\Helper\Config $configHelper
    ) {

        parent::__construct($configHelper);

        $this->connection = $resource->getConnection();
        $this->saveResponseFactory = $saveResponseFactory;
        $this->batchRepository = $batchRepository;
        $this->attributeFactory = $attributeFactory;
        $this->logger = $logger;
    }

    public function post($attribute)
    {
        $this->logger->debug('Attribute::Post was called');
        $response = ($this->batchRepository->post([$attribute], [], [], [], [])->getAttributes())[0];
        $this->logger->debug('Attribute::Post success');
        return $response;
    }

    public function put($magento_id, $attribute)
    {
        $this->logger->debug('Attribute::Put was called');
        $attribute->setMagentoId($magento_id);
        $response = ($this->batchRepository->post([$attribute], [], [], [], [])->getAttributes())[0];
        $this->logger->debug('Attribute::Put success');
        return $response;
    }

    public function delete($magento_id)
    {
        $this->logger->debug('Attribute::Delete was called');
        $attribute = $this->attributeFactory->create();
        $attribute->setMagentoId($magento_id);
        if ($attribute->delete()) {
            $this->logger->debug('Attribute::Delete success');
            return ['message' => 'Entity deleted.', 'errors' => [], 'magento_id' => $attribute->getMagentoId()];
        } else {
            $this->logger->error('Attribute::Delete failed');
            return [
                'message' => 'Could not delete entity.',
                'errors' => ['Unknown error.'],
                'magento_id' => $attribute->getMagentoId()
            ];
        }
    }
}
