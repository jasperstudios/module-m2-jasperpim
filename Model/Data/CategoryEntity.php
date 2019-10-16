<?php

namespace Bluebadger\JasperPim\Model\Data;

use Bluebadger\JasperPim\Model\ValidationException;
use Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator;

class CategoryEntity extends Entity implements \Bluebadger\JasperPim\Api\Data\CategoryInterface
{

    private $isActive = false;
    private $includeInMenu = false;
    private $isAnchor = false;
    private $position = null;
    private $name = [];
    private $description = [];
    private $image = [];
    private $metaTitle = [];
    private $metaKeywords = [];
    private $metaDescription = [];
    private $urlKey = [];
    private $parent = '';

    private $level = 0;
    private $path = '';

    /**
     * @var \Magento\Catalog\Model\CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Catalog\Model\Category
     */
    private $magentoCategory;

    /**
     * @var \Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator
     */
    private $categoryUrlPathGenerator;

    public function __construct(
        \Bluebadger\JasperPim\Helper\Data $helper,
        \Bluebadger\JasperPim\Model\Logger $logger,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Category $magentoCategory,
        \Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator $categoryUrlPathGenerator
    ) {
        parent::__construct($helper, $logger);
        parent::__construct($helper, $logger);
        $this->categoryRepository = $categoryRepository;
        $this->categoryFactory = $categoryFactory;
        $this->storeManager = $storeManager;
        $this->magentoCategory = $magentoCategory;
        $this->categoryUrlPathGenerator = $categoryUrlPathGenerator;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function setIsActive($is_active)
    {
        $this->isActive = $is_active;
        return $this;
    }

    public function getIncludeInMenu()
    {
        return $this->includeInMenu;
    }

    public function setIncludeInMenu($include_in_menu)
    {
        $this->includeInMenu = $include_in_menu;
        return $this;
    }

    public function getIsAnchor()
    {
        return $this->isAnchor;
    }

    public function setIsAnchor($is_anchor)
    {
        $this->isAnchor = $is_anchor;
        return $this;
    }

    public function getPosition()
    {
        return (int) $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    public function setMetaTitle($meta_title)
    {
        $this->metaTitle = $meta_title;
        return $this;
    }

    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    public function setMetaKeywords($meta_keywords)
    {
        $this->metaKeywords = $meta_keywords;
        return $this;
    }

    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    public function setMetaDescription($meta_description)
    {
        $this->metaDescription = $meta_description;
        return $this;
    }

    public function getUrlKey()
    {
        return $this->urlKey;
    }

    public function setUrlKey($url_key)
    {
        $this->urlKey = $url_key;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    protected function validateName()
    {
        $nameValue = $this->helper->getValueForDefaultScope($this->getName());
        if (!$nameValue || !trim($nameValue->getValue())) {
            $this->addValidationException('No default name provided');
            return false;
        }
        return true;
    }

    protected function validateParent()
    {
        if ($parent = trim($this->getParent())) {
            if (!$this->helper->categoryExists($parent)) {
                $this->addValidationException("Parent category not found: '$parent'");
                return false;
            }
        }
        return true;
    }

    protected function _validate()
    {
        return $this->validateName() && $this->validateParent();
    }

    protected function getParentCategory()
    {
        $parentId = $this->getParent();
        if (!$parentId) {
            $parentId = 1;  //  TODO: get default root category
        }
        $connection = $this->helper->getConnection();
        $tableName = $this->helper->getTableName('catalog_category_entity');

        return $connection->fetchRow($connection->select()->from($tableName)->where('entity_id = ?', $parentId));
    }

    protected function getDefaultAttributeSetId()
    {
        $connection = $this->helper->getConnection();
        $tableNameAttributeSet = $this->helper->getTableName('eav_attribute_set');

        $entityTypeId = $this->helper->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
        $attributeSetId = $connection->fetchOne(
            $connection->select()
                ->from($tableNameAttributeSet, 'attribute_set_id')
                ->where('attribute_set_name = ?', 'Default')
                ->where('entity_type_id = ?', $entityTypeId)
        );

        return $attributeSetId;
    }

    protected function getSiblingCount()
    {
        $categoryId = $this->getMagentoId();
        $parentId = $this->getParentCategory()['entity_id'];
        $connection = $this->helper->getConnection();
        $tableName = $this->helper->getTableName('catalog_category_entity');

        $res = $connection->fetchCol(
            $connection->select()
                ->from($tableName, new \Zend_Db_Expr('COUNT(*)'))
                ->where('parent_id = ?', $parentId)
                ->where('entity_id = ?', $categoryId)
        );

        return isset($res[0]) ? $res[0] : 0;
    }

    public function saveEntity()
    {
        $connection = $this->helper->getConnection();

        $categoryTableName = $this->helper->getTableName('catalog_category_entity');

        $categoryId = $this->getMagentoId();
        $parentCategory = $this->getParentCategory();

        $level = $parentCategory['level'] + 1;

        $this->setLevel($level);

        /** @var string $table */
        $tableName = $this->helper->getTableName('catalog_category_entity');

        /** @var array $data */
        $data = [
            'entity_id'        => $categoryId ?: null,
            'attribute_set_id' => $this->getDefaultAttributeSetId(),
            'parent_id'        => $parentCategory['entity_id'],
            'level'            => $level
        ];

        if ($this->getPosition() !== null) {
            $data['position'] = $this->getPosition();
        }

        /** @var string $columnIdentifier */
        $tableHasRowId = $this->helper->tableHasRowId($tableName);

        if ($tableHasRowId) {
            $data['row_id'] = $categoryId;
            $data['created_in'] = 1;
            $data['updated_in'] = \Magento\Staging\Model\VersionManager::MAX_VERSION;
        }

        $fields = array_diff(array_keys($data), ['created_at',' created_in', 'updated_in']);
        $connection->insertOnDuplicate($tableName, $data, $fields);

        if (!$categoryId) {
            $categoryId = $connection->lastInsertId($tableName);
        }

        $this->setPath($parentCategory['path'] . '/' . $categoryId);

        $connection->update($tableName, [
            'path' => $this->getPath(),
        ], $connection->prepareSqlCondition('entity_id', $categoryId));

        $this->setMagentoId($categoryId);

        if ($this->getPosition() === null) {
            $connection->update($tableName, [
                'position' => $this->getSiblingCount(),
            ], $connection->prepareSqlCondition('entity_id', $categoryId) . ' AND position IS NULL');
        }

        // update children_count values for the updated tree
        // TODO: Check for performance issues here
        $treeIds = array_merge(explode('/', $parentCategory['path']), [$categoryId]);
        $select = $connection->select()->from($categoryTableName . ' as e', [
            'e.entity_id',
            new \Zend_Db_Expr(
                '(' .
                    $connection->select()
                        ->from($tableName, new \Zend_Db_Expr('COUNT(*)'))
                        ->where("path LIKE CONCAT(e.path, '/%')")
                        ->__toString() .
                ') AS counted'
            )
        ])->where('entity_id IN (?)', $treeIds);

        foreach ($connection->fetchAll($select) as $record) {
            $connection->update($tableName, [
                'children_count' => $record['counted']
            ], $connection->prepareSqlCondition('entity_id', $record['entity_id']));
        }
    }

    public function saveAttributes()
    {
        $entityId = $this->getMagentoId();
        $attributes = $this->helper->getCategoryAttributes();

        $attributesData = [];

        foreach ([
                    'name' => $this->getName(),
                    'description' => $this->getDescription(),
                    'image' => $this->getImage(),
                    'meta_title' => $this->getMetaTitle(),
                    'meta_keywords' => $this->getMetaKeywords(),
                    'meta_description' => $this->getMetaDescription(),
                    'url_key' => $this->getUrlKey()
                ] as $attribute_code => $values) {

            $attribute = $attributes[$attribute_code];
            if ($values) {
                foreach ($values as $value) {
                    $attribute_be = $attribute['backend'];
                    $attribute_id = $attribute['id'];
                    if (!isset($attributesData[$attribute_be])) {
                        $attributesData[$attribute_be] = [];
                    }
                    if (!isset($attributesData[$attribute_be][$attribute_id])) {
                        $attributesData[$attribute_be][$attribute_id] = [];
                    }
                    $attributesData[$attribute_be][$attribute_id][
                        $this->helper->scopeCodeToStoreId($value->getScope())
                    ] = $value->getValue();
                }
            }
        }

        foreach ([
                'is_active' => $this->getIsActive(),
                'include_in_menu' => $this->getIncludeInMenu(),
                'is_anchor' => $this->getIsAnchor()] as $attributeCode => $attributeValue) {
            $attribute = $attributes[$attributeCode];
            $attributesData[$attribute['backend']][$attribute['id']][0] = $attributeValue;
        }

        $connection = $this->helper->getConnection();
        foreach ($attributesData as $backendType => $valuesByAttribute) {
            $tableName = $this->helper->getTableName('catalog_category_entity_' . $backendType);
            foreach ($valuesByAttribute as $attributeId => $valuesByStore) {
                foreach ($valuesByStore as $storeId => $value) {
                    $data = [
                        'attribute_id' => $attributeId,
                        'store_id' => $storeId,
                        'entity_id' => $entityId,
                        'value' => $value
                    ];
                    $connection->insertOnDuplicate($tableName, $data);
                }
                $connection->query(
                    $connection->deleteFromSelect(
                        $connection->select()
                            ->from($tableName)
                            ->where('attribute_id = ?', $attributeId)
                            ->where('entity_id = ?', $entityId)
                            ->where('store_id NOT IN (?)', array_keys($valuesByStore)),
                        $tableName
                    )
                );
            }
        }
    }

    protected function downloadImages()
    {
        try {
            $images = $this->getImage();
            foreach ($images as $imageIndex => $image) {
                $downloadedFile = $this->helper->saveCategoryImage($image->getValue());
                $images[$imageIndex]->setValue($downloadedFile);
            }
            $this->setImage($images);
        } catch (\Exception $ex) {
            throw new ValidationException('Could not download category image: ' . $ex->getMessage());
        }
    }

    protected function getAttributeValues($attributeCode)
    {
        $connection = $this->helper->getConnection();
        $categoryAttributes = $this->helper->getCategoryAttributes();
        $attribute = $categoryAttributes[$attributeCode];
        $values = $connection->fetchAll($connection->select()->from(
            $this->helper->getTableName('catalog_category_entity_' . $attribute['backend']),
            ['store_id', 'value']
        )->where('attribute_id = ?', $attribute['id'])->where('entity_id = ?', $this->getMagentoId()));
        $return = [];
        foreach ($values as $value) {
            $return[$value['store_id']] = $value['value'];
        }
        return $return;
    }

    protected function saveUrlRewrites()
    {
        $connection = $this->helper->getConnection();
        $entityId   = $this->getMagentoId();
        $urlRewriteTableName = $this->helper->getTableName('url_rewrite');

        $urlKeys = $this->getAttributeValues('url_key');
        $stores = $this->helper->getStores();

        foreach ($stores as $storeId => $store) {

            if (!$urlKeys) {
                continue;
            }

            $urlKey = isset($urlKeys[$storeId]) ? $urlKeys[$storeId] : reset($urlKeys);

            if (!$urlKey) {
                continue;
            }

            $data = [
                'entity_id' => $entityId,
                'url_key'   => $urlKey,
                'url_path'  => $this->getPath(),
                'store_id'  => $storeId,
                'parent_id' => $this->getParent(),
                'level'     => $this->getLevel()
            ];

            $category = $this->magentoCategory;
            $category->setData($data);

            /** @var string $urlPath */
            $urlPath = $this->categoryUrlPathGenerator->getUrlPath($category);

            if (!$urlPath) {
                continue;
            }

            /** @var string $requestPath */
            $requestPath = $this->categoryUrlPathGenerator->getUrlPathWithSuffix($category, $storeId);

            /** @var string|null $exists */
            $exists = $connection->fetchOne(
                $connection->select()
                    ->from($urlRewriteTableName, new \Zend_Db_Expr('COUNT(*)'))
                    ->where('entity_type = ?', CategoryUrlRewriteGenerator::ENTITY_TYPE)
                    ->where('request_path = ?', $requestPath)
                    ->where('store_id = ?', $storeId)
                    ->where('entity_id <> ?', $entityId)
            );

            if ($exists) {
                $category->setUrlKey($category->getUrlKey() . '-' . $storeId);
                $requestPath = $this->categoryUrlPathGenerator->getUrlPathWithSuffix($category, $storeId);
            }

            $rewriteId = $connection->fetchOne(
                $connection->select()
                    ->from($urlRewriteTableName, ['url_rewrite_id'])
                    ->where('entity_type = ?', CategoryUrlRewriteGenerator::ENTITY_TYPE)
                    ->where('entity_id = ?', $entityId)
                    ->where('store_id = ?', $storeId)
            );

            if ($rewriteId) {
                $connection->update(
                    $urlRewriteTableName,
                    ['request_path' => $requestPath],
                    ['url_rewrite_id = ?' => $rewriteId]
                );
            } else {
                $data = [
                    'entity_type'      => CategoryUrlRewriteGenerator::ENTITY_TYPE,
                    'entity_id'        => $entityId,
                    'request_path'     => $requestPath,
                    'target_path'      => 'catalog/category/view/id/' . $entityId,
                    'redirect_type'    => 0,
                    'store_id'         => $storeId,
                    'is_autogenerated' => 1
                ];

                $connection->insertOnDuplicate(
                    $urlRewriteTableName,
                    $data,
                    array_keys($data)
                );
            }
        }
    }

    public function save()
    {

        $this->downloadImages();
        $this->saveEntity();
        $this->saveAttributes();
        $this->saveUrlRewrites();
    }

    public function OLDsave()
    {
        $this->logger->debug('CategoryEntity::save called');
        $categoryId = $this->getMagentoId();

        if ($categoryId) {
            $this->logger->debug('CategoryEntity::save will load by ID');
            $category = $this->categoryRepository->get($categoryId);
            $this->logger->debug('CategoryEntity::save loaded by ID');
        } else {
            $category = $this->categoryFactory->create();
        }

        $this->downloadImages();

        $category->setName($this->helper->getValueForDefaultScope($this->getName())->getValue());
        $category->setDescription($this->helper->getValueForDefaultScope($this->getDescription())->getValue());
        $category->setImage($this->helper->getValueForDefaultScope($this->getImage())->getValue());
        $category->setMetaTitle($this->helper->getValueForDefaultScope($this->getMetaTitle())->getValue());
        $category->setMetaKeywords($this->helper->getValueForDefaultScope($this->getMetaKeywords())->getValue());
        $category->setMetaDescription($this->helper->getValueForDefaultScope($this->getMetaDescription())->getValue());
        $category->setUrlKey(
            $this->helper->getValueForDefaultScope(
                $this->getUrlKey()
            )->getValue() ?: $category->getName()
        );

        $category->setIsActive($this->getIsActive());
        $category->setIncludeInMenu($this->getIncludeInMenu());

        $category->setIsAnchor($this->getIsAnchor());
        $category->setPosition($this->getPosition());

        $this->logger->debug('CategoryEntity::save will save category');

        $this->storeManager->setCurrentStore('admin');
        $category = $this->categoryRepository->save($category);

        $this->logger->debug('CategoryEntity::save saved category');

        $attributeCodes = [
            'name', 'description', 'image', 'meta_title', 'meta_keywords', 'meta_description', 'url_key'
        ];

        foreach ($attributeCodes as $attributeCode) {
            $methodName = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attributeCode)));
            foreach ($this->$methodName() as $scopedValue) {
                if ($scopedValue->getScope() != 'default') {
                    $storeId = $this->helper->scopeCodeToStoreId($scopedValue->getScope());
                    $value   = $scopedValue->getValue();
                    $this->storeManager->setCurrentStore($scopedValue->getScope());
                    $category->setData($attributeCode, $value);
                    $this->categoryRepository->save($category);
                }
            }
        }

        if ($this->getParent()) {
            $this->logger->debug('CategoryEntity::save will move category');
            $parentId = $this->getParent();
            $category->move($parentId, null);
            $this->logger->debug('CategoryEntity::save moved category');
        }

        $this->setMagentoId($category->getId());
    }

    public function delete()
    {
        $this->logger->debug('CategoryEntity::Delete called');
        $magentoId = $this->getMagentoId();
        $connection = $this->helper->getConnection();
        $tableName = $this->helper->getTableName('catalog_category_entity');
        $select = $connection->select()->from($tableName)->where('entity_id = ?', $magentoId);
        $delete = $connection->deleteFromSelect($select, $tableName);
        $this->logger->debug('CategoryEntity::Delete will delete');
        $result = $connection->query($delete);
        $this->logger->debug('CategoryEntity::Delete deleted');
        return $result;
    }
}
