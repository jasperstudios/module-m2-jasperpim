<?php

namespace Bluebadger\JasperPim\Model\Data;

use Bluebadger\JasperPim\Api\Data\AttributeOptionInterface;

class AttributeOptionEntity extends Entity implements AttributeOptionInterface
{

    private $attribute;
    private $labels;
    private $isDefault;
    private $position;

    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $setup;

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resource;

    public function __construct(
        \Bluebadger\JasperPim\Helper\Data $helper,
        \Bluebadger\JasperPim\Model\Logger $logger,
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        parent::__construct($helper, $logger);
        $this->setup = $setup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->resource = $resource;
    }

    public function getAttribute()
    {
        return $this->attribute;
    }

    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
        return $this;
    }

    public function getLabels()
    {
        return $this->labels;
    }

    public function setLabels($labels)
    {
        $this->labels = $labels;
        return $this;
    }

    public function getIsDefault()
    {
        return $this->isDefault;
    }

    public function setIsDefault($is_default)
    {
        $this->isDefault = $is_default;
        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    public function _validate()
    {
        $isValid = true;
        $defaultLabel = $this->helper->getValueForDefaultScope($this->getLabels());
        if (!$defaultLabel || !$defaultLabel->getValue()) {
            $this->addValidationException('No default label defined');
            $isValid = false;
        }

        if (!$this->getAttribute() || !$this->helper->getAttributeIdFromAttributeCode($this->getAttribute())) {
            $this->addValidationException("Could not find attribute '{$this->getAttribute()}'");
            $isValid = false;
        }

        return $isValid;
    }

    public function save()
    {
        $this->logger->debug('AttributeOptionEntity::Save called');
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->setup]);
        $attributeId = $this->helper->getAttributeIdFromAttributeCode($this->getAttribute());
        $optionId = $this->getMagentoId();

        $values = [];
        foreach ($this->getLabels() as $label) {
            $values[$this->helper->scopeCodeToStoreId($label->getScope())] = $label->getValue();
        }

        $option = [
            'attribute_id' => $attributeId,
            'order' => $this->getPosition(),
            'value' => [
                $optionId => $values
            ]
        ];

        $this->logger->debug('AttributeOptionEntity::Save will save attribute option');
        $eavSetup->addAttributeOption($option);
        $this->logger->debug('AttributeOptionEntity::Save saved attribute option');

        if (!$optionId) {
            $this->logger->debug('AttributeOptionEntity::Save it was new, getting ID');
            $connection = $this->helper->getConnection();
            $tableName = $this->helper->getTableName('eav_attribute_option');
            $optionId = $connection->fetchOne(
                $connection->select()->from($tableName, new \Zend_Db_Expr('MAX(`option_id`)'))
            );
        }

        if ($this->getIsDefault()) {
            $connection = $this->helper->getConnection();
            $attributeTableName = $this->helper->getTableName('eav_attribute');
            $connection->update($attributeTableName, [
                'default_value' => $optionId
            ], $connection->prepareSqlCondition('attribute_id', $attributeId));
        }

        $this->setMagentoId($optionId);
    }

    public function delete()
    {
        $this->logger->debug('AttributeOptionEntity::Delete called');

        $magentoId = $this->getMagentoId();
        $connection = $this->helper->getConnection();
        $tableName = $this->helper->getTableName('eav_attribute_option');
        $select = $connection->select()->from($tableName)->where('option_id = ?', $magentoId);
        $delete = $connection->deleteFromSelect($select, $tableName);
        $this->logger->debug('AttributeOptionEntity::Delete will delete');
        $result = $connection->query($delete);
        $this->logger->debug('AttributeOptionEntity::Delete deleted');
        return $result;
    }
}
