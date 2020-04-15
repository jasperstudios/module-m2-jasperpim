<?php

namespace Bluebadger\JasperPim\Helper;

use Bluebadger\JasperPim\Model\TooBusyException;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Config\ConfigOptionsListConstants;

class Data
{

    const DEFAULT_SCOPE_CODE = 'default';

    /**
     * @var \Magento\Store\Model\StoreManager
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    private $connection;

    /**
     * @var \Bluebadger\JasperPim\Api\Data\ScopedValueInterfaceFactory
     */
    private $scopedValueFactory;

    /**
     * @var string
     */
    private $tablePrefix;

    /**
     * @var DeploymentConfig
     */
    private $deploymentConfig;
    /**
     * @var \Magento\CatalogInventory\Model\Configuration
     */
    private $inventoryConfiguration;
    /**
     * @var \Magento\Framework\PhraseFactory
     */
    private $phraseFactory;
    /**
     * @var Media
     */
    private $mediaHelper;
    /**
     * @var \Bluebadger\JasperPim\Model\Logger
     */
    private $logger;
    /**
     * @var array
     */
    private $entityTypeIds = [];
    /**
     * @var array
     */
    private $attributeIds = [];
    /**
     * @var array
     */
    private $storesByCode = [];
    /**
     * @var array
     */
    private $storesById = [];
    /**
     * @var array
     */
    private $categoryAttributes = [];

    public function __construct(
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\App\ResourceConnection $resource,
        \Bluebadger\JasperPim\Api\Data\ScopedValueInterfaceFactory $scopedValueFactory,
        DeploymentConfig $deploymentConfig,
        \Magento\CatalogInventory\Model\Configuration $inventoryConfiguration,
        \Magento\Framework\PhraseFactory $phraseFactory,
        \Bluebadger\JasperPim\Helper\Media $mediaHelper,
        \Bluebadger\JasperPim\Model\Logger $logger
    ) {
        $this->storeManager = $storeManager;
        $this->connection = $resource->getConnection();
        $this->scopedValueFactory = $scopedValueFactory;
        $this->deploymentConfig = $deploymentConfig;
        $this->inventoryConfiguration = $inventoryConfiguration;
        $this->phraseFactory = $phraseFactory;
        $this->mediaHelper = $mediaHelper;
        $this->logger = $logger;
    }

    /**
     * @param string $text
     * @return \Magento\Framework\Phrase
     */
    public function createPhrase($text)
    {
        return $this->phraseFactory->create(['text' => $text]);
    }

    /**
     * @return \Magento\Framework\DB\Adapter\AdapterInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param string $scopeCode
     * @return int
     */
    public function scopeCodeToStoreId($scopeCode)
    {
        if ($scopeCode == self::DEFAULT_SCOPE_CODE) {
            return 0;
        } else {
            if (!$this->storesByCode) {
                $this->storesByCode = $this->storeManager->getStores(false, true);
            }
            if (array_key_exists($scopeCode, $this->storesByCode)) {
                return $this->storesByCode[$scopeCode]->getId();
            }
        }
    }

    public function getStores()
    {
        if (!$this->storesById) {
            $this->storesById = $this->storeManager->getStores();
        }
        return $this->storesById;
    }

    /**
     * @param string $entityTypeCode
     * @return int
     */
    public function getEntityTypeId($entityTypeCode)
    {
        if (!isset($this->entityTypeIds[$entityTypeCode])) {
            $connection = $this->getConnection();
            $tableNameEntityType = $this->getTableName('eav_entity_type');
            $this->entityTypeIds[$entityTypeCode] = $connection->fetchOne(
                $connection->select()
                    ->from($tableNameEntityType, 'entity_type_id')
                    ->where('entity_type_code = ?', $entityTypeCode)
            );
        }
        return (int) $this->entityTypeIds[$entityTypeCode];
    }

    /**
     * @param string $attributeCode
     * @param string $entityTypeCode
     * @return int
     */
    public function getAttributeIdFromAttributeCode(
        $attributeCode,
        $entityTypeCode = \Magento\Catalog\Model\Product::ENTITY
    ) {
        if (!isset($this->attributeIds[$entityTypeCode])) {
            $this->attributeIds[$entityTypeCode] = [];
        }
        if (!isset($this->attributeIds[$entityTypeCode][$attributeCode])) {
            $connection   = $this->getConnection();
            $tableName    = $this->getTableName('eav_attribute');
            $entityTypeId = $this->getEntityTypeId($entityTypeCode);

            $select = $connection->select()
                ->from($tableName)
                ->where('attribute_code = ?', $attributeCode)
                ->where('entity_type_id = ?', $entityTypeId);

            $this->attributeIds[$entityTypeCode][$attributeCode] = $connection->fetchOne($select);
        }
        return (int) $this->attributeIds[$entityTypeCode][$attributeCode];
    }

    /**
     * @param int $productId
     * @return bool
     */
    public function simpleProductExists($productId) {
        $tableName = $this->getTableName('catalog_product_entity');
        $idField = $this->tableHasRowId($tableName) ? 'row_id' : 'entity_id';
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($tableName)
            ->where($idField . ' = ?', $productId)
            ->where('type_id = ?', 'simple');

        return !! $connection->fetchOne($select);
    }

    public function attributeOptionExists($attributeCode, $optionId) {
        $tableName = $this->getTableName('eav_attribute_option');
        $attributeId = $this->getAttributeIdFromAttributeCode($attributeCode);
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($tableName)
            ->where('option_id = ?', $optionId)
            ->where('attribute_id = ?', $attributeId);

        return !! $connection->fetchOne($select);
    }

    /**
     * @param string[] $attributeCodes
     * @param string $entityTypeCode
     * @return int[]
     */
    public function getAttributeIdsFromAttributeCodes(
        $attributeCodes,
        $entityTypeCode = \Magento\Catalog\Model\Product::ENTITY
    ) {
        if (!isset($this->attributeIds[$entityTypeCode])) {
            $this->attributeIds[$entityTypeCode] = [];
        }
        $attributeCodesNotInCache = array_diff($attributeCodes, array_keys($this->attributeIds[$entityTypeCode]));
        if (count($attributeCodesNotInCache)) {
            $entityTypeId = $this->getEntityTypeId($entityTypeCode);
            $connection = $this->getConnection();
            $select = $connection
                ->select()
                ->from($this->getTableName('eav_attribute'), ['attribute_id', 'attribute_code'])
                ->where('attribute_code IN (?)', $attributeCodesNotInCache)
                ->where('entity_type_id', $entityTypeId);

            foreach ($connection->fetchAll($select) as $record) {
                $this->attributeIds[$entityTypeCode][$record['attribute_code']] = $record['attribute_id'];
            }
        }

        return array_intersect_key($this->attributeIds[$entityTypeCode], array_flip($attributeCodes));
    }

    /**
     * @param int $categoryId
     * @return bool
     */
    public function categoryExists($categoryId)
    {
        return $this->allCategoriesExist([$categoryId]);
    }

    /**
     * @param int[] $categoryIds
     * @return bool
     */
    public function allCategoriesExist($categoryIds)
    {
        $connection = $this->getConnection();
        $fromDb = $connection->fetchCol(
            $connection->select()
                ->from($this->getTableName('catalog_category_entity'), 'entity_id')
                ->where('entity_id IN (?)', $categoryIds)
        );
        return !count(array_diff($categoryIds, $fromDb));
    }

    /**
     * @param int $attributeSetId
     * @return bool
     */
    public function attributeSetExists($attributeSetId)
    {
        $connection = $this->getConnection();
        $fromDb = $connection->fetchOne(
            $connection->select()
                ->from($this->getTableName('eav_attribute_set'), 'attribute_set_id')
                ->where('attribute_set_id = ?', $attributeSetId)
        );
        return $fromDb == $attributeSetId;
    }

    /**
     * @return array
     */
    public function getCategoryAttributes()
    {

        if (!$this->categoryAttributes) {
            $attributeCodes = [
                'name', 'description', 'image',
                'meta_title', 'meta_keywords', 'meta_description',
                'url_key',
                'is_active', 'include_in_menu', 'is_anchor'
            ];
            $connection = $this->getConnection();
            $tableNameAttribute = $connection->getTableName('eav_attribute');
            $entityTypeId = $this->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);

            $results = $connection->fetchAll(
                $connection->select()
                    ->from($tableNameAttribute, ['attribute_id', 'attribute_code', 'backend_type'])
                    ->where('attribute_code IN (?)', $attributeCodes)
                    ->where('entity_type_id = ?', $entityTypeId)
            );

            $this->categoryAttributes = [];
            foreach ($results as $record) {
                $this->categoryAttributes[$record['attribute_code']] = [
                    'id' => $record['attribute_id'],
                    'backend' => $record['backend_type']
                ];
            }
        }
        return $this->categoryAttributes;
    }

    /**
     * @param \Bluebadger\JasperPim\Model\Data\ScopedValue[] $scopedValues
     * @return \Bluebadger\JasperPim\Model\Data\ScopedValue
     */
    public function getValueForDefaultScope($scopedValues)
    {
        foreach ($scopedValues as $scopedValue) {
            if ($scopedValue->getScope() == self::DEFAULT_SCOPE_CODE) {
                return $scopedValue;
            }
        }
        $scopedValue = $this->scopedValueFactory->create();
        $scopedValue->setScope(self::DEFAULT_SCOPE_CODE);
        return $scopedValue;
    }

    /**
     * @param string $tableName
     * @return string
     */
    public function getTableName($tableName)
    {
        if ($this->tablePrefix === null) {
            $this->tablePrefix = (string) $this->deploymentConfig->get(
                ConfigOptionsListConstants::CONFIG_PATH_DB_PREFIX
            );
        }
        return $this->tablePrefix . $this->getConnection()->getTableName($tableName);
    }

    /**
     * @param string $table
     * @return bool
     */
    public function tableHasRowId($table)
    {
        return $this->connection->tableColumnExists($table, 'row_id');
    }

    /**
     * @param string $fromUrl
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function saveCategoryImage($fromUrl)
    {
        try {
            $destination = $this->mediaHelper->saveCategoryImage($fromUrl);
            return $destination;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @param string $fromUrl
     * @return string
     * @throws \Exception
     */
    public function saveProductImage($fromUrl)
    {
        try {
            $destination = $this->mediaHelper->saveProductImage($fromUrl);
            return $destination;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }
}
