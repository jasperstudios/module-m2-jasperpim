<?php

namespace Bluebadger\JasperPim\Model;

use Braintree\Exception\TooManyRequests;

class BatchRepository extends AbstractRepository implements \Bluebadger\JasperPim\Api\BatchInterface
{
    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    private $connection;

    /**
     * @var \Bluebadger\JasperPim\Api\Data\SaveResponseInterfaceFactory
     */
    private $saveResponseFactory;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Bluebadger\JasperPim\Model\Data\SaveResponseFactory $saveResponseFactory,
        Logger $logger,
        \Bluebadger\JasperPim\Helper\Config $configHelper
    ) {

        parent::__construct($configHelper);

        $this->connection = $resource->getConnection();
        $this->saveResponseFactory = $saveResponseFactory;
        $this->logger = $logger;
    }

    public function post(
        $attributes = [],
        $attribute_options = [],
        $attribute_sets = [],
        $categories = [],
        $products = []
    ) {
        $args = get_defined_vars(); // <-- This has to stay the first line of the function for it to work correctly

        /**
         * @var \Bluebadger\JasperPim\Model\Data\SaveResponse $response
         */
        $response = $this->saveResponseFactory->create();

        foreach ($args as $entity_type => $entities) {
            /**
             * @var \Bluebadger\JasperPim\Api\Data\EntityInterface $entity
             */
            foreach ($entities as $entity) {
                if ($entity->validate()) {
                    try {
                        $this->logger->debug('will save entity');
                        $entity->save();
                        $this->logger->debug('entity saved');
                        $response->addEntity($entity_type, $entity, 'Entity saved');
                    } catch (\Exception $ex) {
                        $this->logger->error('An error occurred: ' . $ex->getMessage());
                        throw $ex;
                    }
                } else {
                    $response->addEntity($entity_type, $entity, 'Validation failed');
                }
            }
        }
        return $response;
    }
}
