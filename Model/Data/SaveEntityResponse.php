<?php

namespace Bluebadger\JasperPim\Model\Data;

abstract class SaveEntityResponse {
    /**
     * @var SaveResponseEntity
     */
    private $entity;

    /**
     * @var \Bluebadger\JasperPim\Model\Data\SaveResponseEntityFactory
     */
    private $entityFactory;

    public function __construct(\Bluebadger\JasperPim\Model\Data\SaveResponseEntityFactory $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }

    protected function setEntity($entity, $message) {
        $this->entity = $this->entityFactory->create([
            'message' => $message,
            'errors' => $entity->getExceptions(),
            'jasper_id' => $entity->getJasperId(),
            'magento_id' => $entity->getMagentoId()
        ]);
    }

    protected function getEntity() {
        return $this->entity;
    }
}