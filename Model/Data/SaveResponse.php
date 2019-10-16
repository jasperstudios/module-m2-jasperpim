<?php

namespace Bluebadger\JasperPim\Model\Data;

class SaveResponse implements \Bluebadger\JasperPim\Api\Data\SaveResponseInterface
{

    /**
     * @var ResponseEntity[]
     */
    private $attributes = [];
    /**
     * @var ResponseEntity[]
     */
    private $attribute_options = [];
    /**
     * @var ResponseEntity[]
     */
    private $attribute_sets = [];
    private $categories = [];
    private $products = [];

    /**
     * @var ResponseEntityFactory
     */
    private $entityFactory;

    public function __construct(\Bluebadger\JasperPim\Model\Data\ResponseEntityFactory $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }

    public function addEntity($entity_type, $entity, $message)
    {
        $this->$entity_type[] = $this->entityFactory->create([
            'message' => $message,
            'errors' => $entity->getExceptions(),
            'magento_id' => $entity->getMagentoId()
        ]);
    }

    /**
     * @return ResponseEntity[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return ResponseEntity[]
     */
    public function getAttributeOptions()
    {
        return $this->attribute_options;
    }
    /**
     * @return ResponseEntity[]
     */
    public function getAttributeSets()
    {
        return $this->attribute_sets;
    }
    /**
     * @return ResponseEntity[]
     */
    public function getCategories()
    {
        return $this->categories;
    }
    /**
     * @return ResponseEntity[]
     */
    public function getProducts()
    {
        return $this->products;
    }
}
