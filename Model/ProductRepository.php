<?php

namespace Bluebadger\JasperPim\Model;

class ProductRepository extends AbstractRepository implements \Bluebadger\JasperPim\Api\ProductInterface
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
     * @var \Bluebadger\JasperPim\Api\Data\ProductInterfaceFactory
     */
    private $productFactory;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Bluebadger\JasperPim\Api\Data\ResponseEntityInterfaceFactory $saveResponseFactory,
        \Bluebadger\JasperPim\Api\BatchInterface $batchRepository,
        \Bluebadger\JasperPim\Api\Data\ProductInterfaceFactory $productFactory,
        \Bluebadger\JasperPim\Helper\Config $configHelper
    ) {

        parent::__construct($configHelper);

        $this->connection = $resource->getConnection();
        $this->saveResponseFactory = $saveResponseFactory;
        $this->batchRepository = $batchRepository;
        $this->productFactory = $productFactory;
    }

    public function post($product)
    {
        $response = ($this->batchRepository->post([], [], [], [], [$product])->getProducts())[0];
        return $response;
    }

    public function put($magento_id, $product)
    {
        $product->setMagentoId($magento_id);
        $response = ($this->batchRepository->post([], [], [], [], [$product])->getProducts())[0];
        return $response;
    }

    public function delete($magento_id)
    {
        $product = $this->productFactory->create();
        $product->setMagentoId($magento_id);
        $product->delete();
        return true;
    }
}
