<?php

namespace Bluebadger\JasperPim\Model\Data;

use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class AttributeSetEntity extends Entity implements \Bluebadger\JasperPim\Api\Data\AttributeSetInterface
{
    private $name;
    private $groups;

    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $setup;
    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;
    /**
     * @var \Magento\Eav\Model\AttributeSetRepository
     */
    private $attributeSetRepository;
    /**
     * @var \Magento\Eav\Model\Attribute\GroupRepository
     */
    private $attributeGroupRepository;

    public function __construct(
        \Bluebadger\JasperPim\Helper\Data $helper,
        \Bluebadger\JasperPim\Model\Logger $logger,
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        \Magento\Eav\Model\AttributeSetRepository $attributeSetRepository,
        \Magento\Eav\Model\Attribute\GroupRepository $attributeGroupRepository
    ) {
        parent::__construct($helper, $logger);
        $this->setup = $setup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->attributeSetRepository = $attributeSetRepository;
        $this->attributeGroupRepository = $attributeGroupRepository;
    }

    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getGroups()
    {
        return $this->groups;
    }
    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    protected function validateName()
    {
        if (!trim($this->getName())) {
            $this->addValidationException('No name provided');
            return false;
        }
        return true;
    }
    protected function validateGroups()
    {
        $allAttributeCodes = [];
        foreach ($this->getGroups() as $group) {
            if (!trim($group->getName())) {
                $this->addValidationException('No group name provided');
                return false;
            }
            foreach ($group->getAttributes() as $attributeCode) {
                $allAttributeCodes[$attributeCode] = true;
            }
        }
        $allAttributeCodes = array_keys($allAttributeCodes);

        $allAttributeIds = $this->helper->getAttributeIdsFromAttributeCodes($allAttributeCodes);

        $diff = array_diff($allAttributeCodes, array_keys(array_filter($allAttributeIds)));
        if ($diff) {
            foreach ($diff as $d) {
                $this->addValidationException('Attribute not found: ' . $d);
            }
            return false;
        }
        return true;
    }

    protected function _validate()
    {
        return $this->validateName() && $this->validateGroups();
    }

    public function save()
    {
        $this->logger->debug('AttributeSetEntity::save called');
        $magentoId = $this->getMagentoId();
        $attributeSet = false;
        if ($magentoId) {
            $this->logger->debug('AttributeSetEntity::save loading attribute set by ID');
            $attributeSet = $this->attributeSetRepository->get($magentoId);
            $this->logger->debug('AttributeSetEntity::save loaded attribute set by ID');
        }

        if (!$attributeSet) {
            $attributeSet = $this->attributeSetFactory->create();
        }

        /**
         * @var \Magento\Eav\Setup\EavSetup $eavSetup
         */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->setup]);

        $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);

        $attributeSet->setName($this->getName());
        $attributeSet->setEntityTypeId($entityTypeId);

        $this->logger->debug('AttributeSetEntity::save will validate and save attribute set');
        $attributeSet->validate();
        $this->logger->debug('AttributeSetEntity::save validated attribute set');
        $this->attributeSetRepository->save($attributeSet);
        $this->logger->debug('AttributeSetEntity::save saved attribute set');

        $attributeSetId = $attributeSet->getId();
        $existingGroups = $eavSetup->getAttributeGroupCollectionFactory()
            ->addFieldToFilter('attribute_set_id', $attributeSetId);
        $groupsToRemove = $existingGroups->getAllIds();

        foreach ($this->getGroups() as $groupIndex => $group) {
            $groupName = $group->getName();
            $this->logger->debug('AttributeSetEntity::save will save attribute group');
            $eavSetup->addAttributeGroup($entityTypeId, $attributeSetId, $groupName, $groupIndex);
            $this->logger->debug('AttributeSetEntity::save saved attribute group');
            $attributeGroupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $groupName);
            $groupsToRemove = array_diff($groupsToRemove, [$attributeGroupId]);
            $this->logger->debug('AttributeSetEntity::save loaded attribute group');
            $attributeIds = $this->helper->getAttributeIdsFromAttributeCodes($group->getAttributes());
            foreach ($group->getAttributes() as $attributeIndex => $attributeCode) {
                $attributeId = $attributeIds[$attributeCode];
                $this->logger->debug('AttributeSetEntity::save will add attribute to group');
                $eavSetup->addAttributeToGroup(
                    $entityTypeId,
                    $attributeSetId,
                    $attributeGroupId,
                    $attributeId,
                    $attributeIndex
                );
                $this->logger->debug('AttributeSetEntity::save added attribute to group');
            }
        }
        foreach ($groupsToRemove as $groupToRemove) {
            $eavSetup->removeAttributeGroup($entityTypeId, $attributeSetId, $groupToRemove);
        }
        $this->setMagentoId($attributeSetId);
    }
}
