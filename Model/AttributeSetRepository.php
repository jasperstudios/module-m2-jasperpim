<?php

namespace Bluebadger\JasperPim\Model;

class AttributeSetRepository extends AbstractRepository implements \Bluebadger\JasperPim\Api\AttributeSetInterface
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
     * @var \Bluebadger\JasperPim\Api\Data\AttributeSetInterfaceFactory
     */
    private $attributeSetFactory;

    /**
     * @var \Magento\Framework\PhraseFactory
     */
    private $phraseFactory;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Bluebadger\JasperPim\Api\Data\ResponseEntityInterfaceFactory $saveResponseFactory,
        \Bluebadger\JasperPim\Api\BatchInterface $batchRepository,
        \Bluebadger\JasperPim\Api\Data\AttributeSetInterfaceFactory $attributeSetFactory,
        \Magento\Framework\PhraseFactory $phraseFactory,
        \Bluebadger\JasperPim\Helper\Config $configHelper
    ) {

        parent::__construct($configHelper);

        $this->connection = $resource->getConnection();
        $this->saveResponseFactory = $saveResponseFactory;
        $this->batchRepository = $batchRepository;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->phraseFactory = $phraseFactory;
    }

    public function post($attribute_set)
    {
        $response = ($this->batchRepository->post([], [], [$attribute_set], [], [])->getAttributeSets())[0];
        return $response;
    }

    public function put($magento_id, $attribute_set)
    {
        $attribute_set->setMagentoId($magento_id);
        $response = ($this->batchRepository->post([], [], [$attribute_set], [], [])->getAttributeSets())[0];
        return $response;
    }

    public function delete($jasper_id)
    {
        throw new \Bluebadger\JasperPim\Model\Exception(
            $this->phraseFactory->create([
                'text' => 'Delete an attribute set is not allowed.'
            ]),
            0,
            \Magento\Framework\Webapi\Exception::HTTP_METHOD_NOT_ALLOWED
        );
    }
}
