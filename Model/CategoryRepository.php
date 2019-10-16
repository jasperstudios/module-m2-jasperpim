<?php

namespace Bluebadger\JasperPim\Model;

class CategoryRepository extends AbstractRepository implements \Bluebadger\JasperPim\Api\CategoryInterface
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
     * @var \Bluebadger\JasperPim\Api\Data\CategoryInterfaceFactory
     */
    private $categoryFactory;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Bluebadger\JasperPim\Api\Data\ResponseEntityInterfaceFactory $saveResponseFactory,
        \Bluebadger\JasperPim\Api\BatchInterface $batchRepository,
        \Bluebadger\JasperPim\Api\Data\CategoryInterfaceFactory $categoryFactory,
        \Bluebadger\JasperPim\Helper\Config $configHelper
    ) {

        parent::__construct($configHelper);

        $this->connection = $resource->getConnection();
        $this->saveResponseFactory = $saveResponseFactory;
        $this->batchRepository = $batchRepository;
        $this->categoryFactory = $categoryFactory;
    }

    public function post($category)
    {
        $response = ($this->batchRepository->post([], [], [], [$category], [])->getCategories())[0];
        return $response;
    }

    public function put($magento_id, $category)
    {
        $category->setMagentoId($magento_id);
        $response = ($this->batchRepository->post([], [], [], [$category], [])->getCategories())[0];
        return $response;
    }

    public function delete($magento_id)
    {
        $category = $this->categoryFactory->create();
        $category->setMagentoId($magento_id);
        $category->delete();
        return true;
    }
}
