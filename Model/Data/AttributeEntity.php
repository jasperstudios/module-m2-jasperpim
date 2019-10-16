<?php

namespace Bluebadger\JasperPim\Model\Data;

class AttributeEntity extends Entity implements \Bluebadger\JasperPim\Api\Data\AttributeInterface
{
    private $attributeCode = '';
    private $labels = [];
    private $inputType = 'text';
    private $required = false;
    private $scope = 'global';
    private $unique = false;
    private $validation = 'none';
    private $isUsedInGrid = false;
    private $isFilterableInGrid = false;

    /**
     * @var AttributeFrontend
     */
    private $frontend;

    private $availableInputTypes = [
        'text', 'textarea', 'date', 'boolean', 'multiselect', 'select',
        'price', 'media_image', 'swatch_visual', 'swatch_text', 'weee'
    ];

    private $backendTypes = [
        'text'          => 'varchar',
        'textarea'      => 'text',
        'date'          => 'datetime',
        'boolean'       => 'int',
        'multiselect'   => 'varchar',
        'select'        => 'int',
        'price'         => 'decimal',
        'media_image'   => 'varchar',
        'swatch_visual' => 'varchar',
        'swatch_text'   => 'varchar',
        'weee'          => 'decimal'
    ];

    private $availableScopes = [
        'global', 'website', 'store'
    ];

    private $availableValidations = [
        'none', 'number', 'digits', 'email', 'url', 'alpha', 'alphanum'
    ];

    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $setup;

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Repository
     */
    private $productAttributeRepository;

    /**
     * @var \Magento\Eav\Api\Data\AttributeFrontendLabelInterfaceFactory
     */
    private $attributeFrontendLabelFactory;

    public function __construct(
        \Bluebadger\JasperPim\Api\Data\AttributeFrontendInterfaceFactory $frontendFactory,
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository,
        \Magento\Eav\Api\Data\AttributeFrontendLabelInterfaceFactory $attributeFrontendLabelFactory,
        \Bluebadger\JasperPim\Helper\Data $helper,
        \Bluebadger\JasperPim\Model\Logger $logger
    ) {
        $this->frontend = $frontendFactory->create();
        $this->setup = $setup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->attributeFrontendLabelFactory = $attributeFrontendLabelFactory;
        parent::__construct($helper, $logger);
    }

    public function getAttributeCode()
    {
        return $this->attributeCode;
    }

    public function setAttributeCode($attribute_code)
    {
        $this->attributeCode = $attribute_code;
    }

    public function getLabels()
    {
        return $this->labels;
    }

    public function setLabels($labels)
    {
        $this->labels = $labels;
    }

    public function getInputType()
    {
        return $this->inputType;
    }

    public function setInputType($input_type)
    {
        $this->inputType = $input_type;
    }

    public function getRequired()
    {
        return $this->required;
    }

    public function setRequired($required)
    {
        $this->required = $required;
    }

    public function getScope()
    {
        return $this->scope;
    }

    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    public function getUnique()
    {
        return $this->unique;
    }

    public function setUnique($unique)
    {
        $this->unique = $unique;
    }

    public function getValidation()
    {
        return $this->validation;
    }

    public function setValidation($validation)
    {
        $this->validation = $validation;
    }

    public function getIsUsedInGrid()
    {
        return $this->isUsedInGrid;
    }

    public function setIsUsedInGrid($is_used_in_grid)
    {
        $this->isUsedInGrid = $is_used_in_grid;
    }

    public function getIsFilterableInGrid()
    {
        return $this->isFilterableInGrid;
    }

    public function setIsFilterableInGrid($is_filterable_in_grid)
    {
        $this->isFilterableInGrid = $is_filterable_in_grid;
    }

    public function getFrontend()
    {
        return $this->frontend;
    }

    public function setFrontend($frontend)
    {
        $this->frontend = $frontend;
    }

    protected function getBackendType()
    {
        return $this->backendTypes[$this->getInputType()];
    }

    protected function validateAttributeCode()
    {
        if (!trim($this->attributeCode)) {
            $this->addValidationException('No attribute_code provided');
            return false;
        }
        return true;
    }

    protected function validateLabels()
    {
        if (!$this->labels || !count($this->labels)) {
            $this->addValidationException('No label provided');
            return false;
        }

        $defaultLabel = $this->helper->getValueForDefaultScope($this->labels);
        if (!$defaultLabel || !$defaultLabel->getValue()) {
            // No default label
            $this->addValidationException('No default label provided');
            return false;
        }
        return true;
    }

    protected function validateInputType()
    {
        if (!in_array($this->inputType, $this->availableInputTypes)) {
            $this->addValidationException('Invalid "input_type" value');
            return false;
        }
        return true;
    }

    protected function validateScope()
    {
        if (!in_array($this->scope, $this->availableScopes)) {
            $this->addValidationException('Invalid "scope" value');
            return false;
        }
        return true;
    }

    protected function validateValidation()
    {
        if (!in_array($this->validation, $this->availableValidations)) {
            $this->addValidationException('Invalid "validation" value');
            return false;
        }
        return true;
    }

    protected function validateFrontend()
    {
        if (!in_array(
            $this->frontend->getLayeredNavigationCanonical(),
            $this->frontend->getAvailableLayeredNavigationCanonical()
        )) {
            $this->addValidationException('Invalid "frontend"');
            return false;
        }
        return true;
    }

    protected function _validate()
    {
        $this->validateAttributeCode();
        $this->validateLabels();
        $this->validateInputType();
        $this->validateScope();
        $this->validateValidation();
        $this->validateFrontend();

        return count($this->getExceptions()) == 0;
    }

    public function save()
    {
        $this->logger->debug('AttributeEntity::save called');
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->setup]);
        $this->logger->debug('AttributeEntity::save will save attribute');

        $scope = 'global';
        if (in_array($this->getScope(), ['website', 'store'])) {
            $scope = $this->getScope();
        };
        $constantName = 'SCOPE_' . strtoupper($scope);
        $scope = constant("\Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::$constantName");

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            $this->getAttributeCode(),
            [
                'backend' => '',
                'frontend' => '',
                'label' => $this->helper->getValueForDefaultScope($this->labels)->getValue(),
                'input' => $this->getInputType(),
                'type' => $this->getBackendType(),
                'class' => '',
                'source' => '',
                'global' => $scope,
                'visible' => true,
                'required' => $this->getRequired(),
                'user_defined' => true,
                'default' => '',
                'searchable' => $this->getFrontend()->getIsSearchable(),
                'filterable' => $this->getFrontend()->getIsFilterable(),
                'filterable_in_search' => $this->getFrontend()->getIsFilterableInSearch(),
                'comparable' => $this->getFrontend()->getIsComparable(),
                'visible_on_front' => $this->getFrontend()->getIsVisibleOnFront(),
                'used_in_product_listing' => $this->getFrontend()->getUsedInProductListing(),
                'used_for_sort_by' => $this->getFrontend()->getUsedForSortBy(),
                'unique' => $this->getUnique(),
                'is_filterable_in_grid' => $this->getIsFilterableInGrid(),
                'position' => $this->getFrontend()->getPosition(),
                'is_html_allowed_on_front' => $this->getFrontend()->getIsHtmlAllowedOnFront(),
                'apply_to' => ''
            ]
        );

        $this->logger->debug('AttributeEntity::save will load attribute');
        $attribute = $this->productAttributeRepository->get($this->getAttributeCode());

        $attribute->setIsUsedForPromoRules($this->getFrontend()->getIsUsedForPromoRules());
        $attribute->setIsUsedInGrid($this->getIsUsedInGrid());
        $attribute->setIsVisibleInAdvancedSearch($this->getFrontend()->getIsVisibleInAdvancedSearch());
        $this->logger->debug('AttributeEntity::save will re-save attribute');
        $this->productAttributeRepository->save($attribute);
        $this->logger->debug('AttributeEntity::save attribute re-saved');

        if (count($this->getLabels()) > 1) {
            $frontendLabels = [];
            foreach ($this->getLabels() as $label) {
                $storeId = $this->helper->scopeCodeToStoreId($label->getScope());
                if ($storeId) {
                    $frontendLabels[] =
                        $this->attributeFrontendLabelFactory->create()
                            ->setStoreId($storeId)
                            ->setLabel($label->getValue());
                }
            }
            $attribute->setFrontendLabels($frontendLabels);
            $this->logger->debug('AttributeEntity::save will save attribute labels');
            $this->productAttributeRepository->save($attribute);
        }

        $this->setMagentoId($attribute->getAttributeId());

        return $this;
    }

    public function delete()
    {
        $this->logger->debug('AttributeEntity::Delete called');

        if (!$this->getMagentoId()) {
            $this->addValidationException('Could not find entity.');
            return false;
        }
        $connection = $this->helper->getConnection();

        $tableName = $this->helper->getTableName('eav_attribute');
        $select = $connection->select()->from($tableName)->where('attribute_id = ?', $this->getMagentoId());
        $delete = $connection->deleteFromSelect($select, $tableName);
        $this->logger->debug('AttributeEntity::Delete will delete');
        $result =  $connection->query($delete);
        $this->logger->debug('AttributeEntity::Delete deleted');
        return $result;
    }
}
