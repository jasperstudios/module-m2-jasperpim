<?php

namespace Bluebadger\JasperPim\Model\Data;

use Bluebadger\JasperPim\Model\ValidationException;
use Magento\CatalogUrlRewrite\Model\ProductUrlPathGenerator;
use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;
use Zend_Db_Expr as Expr;

class ProductEntity extends Entity implements \Bluebadger\JasperPim\Api\Data\ProductInterface
{
    const TYPE_SIMPLE = 'simple';
    const TYPE_CONFIGURABLE = 'configurable';

    private $type = 'simple';
    private $sku = '';
    private $attributeSet = '';
    private $attributes = [];
    private $images = [];
    private $websites = null;
    private $pricing;
    private $inventory;
    private $categories = [];
    private $configurations;

    /**
     * @var \Magento\Catalog\Model\Product\Factory
     */
    private $productFactory;
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    private $productRepository;
    /**
     * @var \Magento\Catalog\Model\Product
     */
    private $magentoProduct;
    /**
     * @var ProductUrlPathGenerator
     */
    private $productUrlPathGenerator;
    /**
     * @var \Bluebadger\JasperPim\Helper\Config
     */
    private $configHelper;
    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    private $cacheManager;

    public function __construct(
        \Bluebadger\JasperPim\Api\Data\ProductPricingInterfaceFactory $productPricingFactory,
        \Bluebadger\JasperPim\Api\Data\ProductInventoryInterfaceFactory $productInventoryFactory,
        \Bluebadger\JasperPim\Api\Data\ProductConfigurationsInterfaceFactory $productConfigurationsFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Model\Product $magentoProduct,
        \Bluebadger\JasperPim\Helper\Data $helper,
        \Bluebadger\JasperPim\Helper\Config $configHelper,
        \Bluebadger\JasperPim\Model\Logger $logger,
        ProductUrlPathGenerator $productUrlPathGenerator,
        \Magento\Framework\App\CacheInterface $cacheManager
    ) {
        $this->pricing = $productPricingFactory->create();
        $this->inventory = $productInventoryFactory->create();
        $this->configurations = $productConfigurationsFactory->create();
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->magentoProduct = $magentoProduct;
        $this->productUrlPathGenerator = $productUrlPathGenerator;
        $this->configHelper = $configHelper;
        $this->cacheManager = $cacheManager;

        parent::__construct($helper, $logger);
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
        return $this;
    }

    public function getAttributeSet()
    {
        return $this->attributeSet;
    }

    public function setAttributeSet($attribute_set)
    {
        $this->attributeSet = $attribute_set;
        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setImages($images)
    {
        $this->images = $images;
        return $this;
    }

    public function getWebsites()
    {
        return $this->websites;
    }

    public function setWebsites($websites)
    {
        $this->websites = $websites;
        return $this;
    }

    public function getPricing()
    {
        return $this->pricing;
    }

    public function setPricing($pricing)
    {
        $this->pricing = $pricing;
        return $this;
    }

    public function getInventory()
    {
        return $this->inventory;
    }

    public function setInventory($inventory)
    {
        $this->inventory = $inventory;
        return $this;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function setCategories($categories)
    {
        $this->categories = $categories;
        return $this;
    }

    public function getConfigurations()
    {
        return $this->configurations;
    }

    public function setConfigurations($configurations)
    {
        $this->configurations = $configurations;
        return $this;
    }

    protected function _validate()
    {
        if (!$this->getSku()) {
            $this->addValidationException('No sku provided');
            return false;
        }
        if (!$this->getAttributeSet()) {
            $this->addValidationException('No attribute_set provided');
            return false;
        }
        if (!$this->helper->attributeSetExists($this->getAttributeSet())) {
            $attributeSet = $this->getAttributeSet();
            $this->addValidationException("Invalid attribute_set provided: '$attributeSet', please first create it");
            return false;
        }

        // configurable-specific validation
        if($this->getType() == self::TYPE_CONFIGURABLE) {
            foreach($this->getConfigurations()->getSuperAttributes() as $indexAssociated => $superAttribute) {
                if(!$this->helper->getAttributeIdFromAttributeCode($superAttribute)) {
                    $this->addValidationException("Associated product error [$indexAssociated]: Attribute '$superAttribute' does not exist");
                    return false;
                }
            }
            foreach($this->getConfigurations()->getAssociatedProducts() as $indexAssociated => $associatedProduct) {
                $productId = (int)$associatedProduct;
                if(!$this->helper->simpleProductExists($productId)) {
                    $this->addValidationException("Associated product error [$indexAssociated]: Simple product with ID '$productId' does not exist");
                    return false;
                }
            }
        }
        if (!$this->helper->allCategoriesExist($this->getCategories())) {
            $this->addValidationException("Not all categories exist, please check 'categories'");
            return false;
        }

        return true;
    }

    public function save()
    {
        $this->logger->debug('ProductEntity::save called');

        $this->logger->debug('ProductEntity::save will save entity');
        $this->saveEntity();
        $this->logger->debug('ProductEntity::save entity saved');

        $this->logger->debug('ProductEntity::save will save attributes');
        $this->saveAttributes();
        $this->logger->debug('ProductEntity::save attributes saved');

        $this->logger->debug('ProductEntity::save will save images');
        $this->saveImages();
        $this->logger->debug('ProductEntity::save images saved');

        $this->logger->debug('ProductEntity::save will save websites');
        $this->saveWebsites();
        $this->logger->debug('ProductEntity::save websites saved');

        $this->logger->debug('ProductEntity::save will save pricing');
        $this->savePricing();
        $this->logger->debug('ProductEntity::save pricing saved');

        $this->logger->debug('ProductEntity::save will save inventory');
        $this->saveInventory();
        $this->logger->debug('ProductEntity::save inventory saved');

        $this->logger->debug('ProductEntity::save will save categories');
        $this->saveCategories();
        $this->logger->debug('ProductEntity::save categories saved');

        if($this->getType() == self::TYPE_CONFIGURABLE) {
            $this->logger->debug('ProductEntity::save will save configurations');
            $this->saveConfigurations();
            $this->logger->debug('ProductEntity::save configurations saved');

            $this->logger->debug('ProductEntity::save will clean cache for configurable product');
            $this->cacheManager->clean(['configurable_' . $this->getMagentoId()]);
            $this->logger->debug('ProductEntity::save cleaned cache for configurable product');
        }

        $this->logger->debug('ProductEntity::save will save url rewrites');
        $this->saveUrlRewrites();
        $this->logger->debug('ProductEntity::save url rewrites saved');
    }

    protected function getUrlAttributeValues()
    {
        $attributeCodes = ['url_key', 'name'];
        $connection = $this->helper->getConnection();
        $productAttributes = $this->helper->getAttributeIdsFromAttributeCodes($attributeCodes);

        $values = $connection->fetchAll($connection->select()
            ->from(
                $this->helper->getTableName('catalog_product_entity_varchar'),
                ['attribute_id', 'store_id', 'value']
            )->where('attribute_id IN (?)', $productAttributes)->where('entity_id = ?', $this->getMagentoId()));
        $return = array_combine($attributeCodes, [[],[]]);
        foreach ($values as $value) {
            $return[array_search($value['attribute_id'], $productAttributes)][$value['store_id']] = $value['value'];
        }
        return $return;
    }

    protected function saveUrlRewrites()
    {
        $connection = $this->helper->getConnection();

        $urlRewriteTableName = $this->helper->getTableName('url_rewrite');

        $entityId = $this->getMagentoId();

        $attributeValues = $this->getUrlAttributeValues();
        $urlKeys = isset($attributeValues['url_key']) ? $attributeValues['url_key'] : null;
        $names = isset($attributeValues['name']) ? $attributeValues['name'] : null;

        foreach ($this->helper->getStores() as $storeId => $store) {

            $urlKey = false;
            if ($urlKeys) {
                if (isset($urlKeys[$storeId])) {
                    $urlKey = $urlKeys[$storeId];
                } elseif (isset($urlKeys[0])) {
                    $urlKey = $urlKeys[0];
                }
            }
            if (!$urlKey && $names) {
                if (isset($names[$storeId])) {
                    $urlKey = $names[$storeId];
                } elseif (isset($names[0])) {
                    $urlKey = $names[0];
                }
            }

            if (!$urlKey) {
                continue;
            }

            $data = [
                'entity_id' => $entityId,
                'url_key'   => $urlKey,
                'store_id'  => $storeId
            ];

            $product = $this->magentoProduct;
            $product->setData($data);

            /** @var string $urlPath */
            $urlPath = $this->productUrlPathGenerator->getUrlPath($product);

            if (!$urlPath) {
                continue;
            }

            /** @var string $requestPath */
            $requestPath = $this->productUrlPathGenerator->getUrlPathWithSuffix($product, $storeId);

            /** @var string|null $exists */
            $exists = $connection->fetchOne(
                $connection->select()->from($urlRewriteTableName, new Expr('COUNT(*)'))
                    ->where('entity_type = ?', ProductUrlRewriteGenerator::ENTITY_TYPE)
                    ->where('request_path = ?', $requestPath)
                    ->where('store_id = ?', $product->getStoreId())
                    ->where('entity_id <> ?', $product->getEntityId())
            );

            if ($exists) {
                $product->setUrlKey($product->getUrlKey() . '-' . $storeId);
                $requestPath = $this->productUrlPathGenerator->getUrlPathWithSuffix($product, $storeId);
            }

            /** @var array $paths */
            $paths = [
                $requestPath => [
                    'request_path' => $requestPath,
                    'target_path'  => 'catalog/product/view/id/' . $entityId,
                    'metadata'     => null,
                    'category_id'  => null
                ]
            ];

            /** @var bool $categoryIsUsedInProductUrl */
            $categoryIsUsedInProductUrl = $this->configHelper->getCategoryIsUsedInProductUrl($storeId);

            if ($categoryIsUsedInProductUrl) {
                /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $categories */
                $categories = $product->getCategoryCollection()->addAttributeToSelect('url_key');

                /** @var CategoryModel $category */
                foreach ($categories as $category) {
                    $categoryId = $category->getId();
                    /** @var string $requestPath */
                    $requestPath         = $this->productUrlPathGenerator->getUrlPathWithSuffix(
                        $product,
                        $storeId,
                        $category
                    );
                    $paths[$requestPath] = [
                        'request_path' => $requestPath,
                        'target_path'  => 'catalog/product/view/id/' . $entityId . '/category/' . $categoryId,
                        'metadata'     => '{"category_id":"' . $categoryId . '"}',
                        'category_id'  => $categoryId
                    ];
                    $parents             = $category->getParentCategories();
                    foreach ($parents as $parent) {
                        $parentId = $parent->getId();
                        $requestPath =
                            $this->productUrlPathGenerator->getUrlPathWithSuffix($product, $storeId, $parent);
                        if (isset($paths[$requestPath])) {
                            continue;
                        }
                        $paths[$requestPath] = [
                            'request_path' => $requestPath,
                            'target_path'  => 'catalog/product/view/id/' . $entityId . '/category/' . $parentId,
                            'metadata'     => '{"category_id":"' . $parentId . '"}',
                            'category_id'  => $parentId
                        ];
                    }
                }
            }

            foreach ($paths as $path) {
                if (!isset($path['request_path'], $path['target_path'])) {
                    continue;
                }
                /** @var string $requestPath */
                $requestPath = $path['request_path'];
                /** @var string $targetPath */
                $targetPath = $path['target_path'];
                /** @var string $metadata */
                $metadata = $path['metadata'];

                /** @var string|null $rewriteId */
                $rewriteId = $connection->fetchOne(
                    $connection->select()
                        ->from($urlRewriteTableName, ['url_rewrite_id'])
                        ->where('entity_type = ?', ProductUrlRewriteGenerator::ENTITY_TYPE)
                        ->where('target_path = ?', $targetPath)
                        ->where('entity_id = ?', $entityId)
                        ->where('store_id = ?', $storeId)
                );

                if ($rewriteId) {
                    $connection->update(
                        $urlRewriteTableName,
                        ['request_path' => $requestPath, 'metadata' => $metadata],
                        ['url_rewrite_id = ?' => $rewriteId]
                    );
                } else {
                    /** @var array $data */
                    $data = [
                        'entity_type'      => ProductUrlRewriteGenerator::ENTITY_TYPE,
                        'entity_id'        => $entityId,
                        'request_path'     => $requestPath,
                        'target_path'      => $targetPath,
                        'redirect_type'    => 0,
                        'store_id'         => $storeId,
                        'is_autogenerated' => 1,
                        'metadata'         => $metadata
                    ];

                    $connection->insertOnDuplicate(
                        $urlRewriteTableName,
                        $data,
                        array_keys($data)
                    );

                    if ($categoryIsUsedInProductUrl && $path['category_id']) {
                        /** @var int $rewriteId */
                        $rewriteId = $connection->fetchOne(
                            $connection->select()
                                ->from($urlRewriteTableName, ['url_rewrite_id'])
                                ->where('entity_type = ?', ProductUrlRewriteGenerator::ENTITY_TYPE)
                                ->where('target_path = ?', $targetPath)
                                ->where('entity_id = ?', $entityId)
                                ->where('store_id = ?', $storeId)
                        );
                    }
                }

                if ($categoryIsUsedInProductUrl && $rewriteId && $path['category_id']) {

                    $urlRewriteProductCategoryTableName =
                        $this->helper->getTableName('catalog_url_rewrite_product_category');

                    $data = [
                        'url_rewrite_id' => $rewriteId,
                        'category_id'    => $path['category_id'],
                        'product_id'     => $entityId
                    ];
                    $connection->delete(
                        $urlRewriteProductCategoryTableName,
                        ['url_rewrite_id = ?' => $rewriteId]
                    );
                    $connection->insertOnDuplicate(
                        $urlRewriteProductCategoryTableName,
                        $data,
                        array_keys($data)
                    );
                }
            }
        }
    }

    protected function saveConfigurations() {
//        print_r($this->getAssociatedProducts());

        /** @var AdapterInterface $connection */
        $connection = $this->helper->getConnection();

        $valuesLabels = [];
        $valuesRelations = []; // catalog_product_relation
        $valuesSuperLink = []; // catalog_product_super_link
        $stores = $this->helper->getStores();

        $entityId = $this->getMagentoId();

        $tableNameSuperAttribute = $this->helper->getTableName('catalog_product_super_attribute');
        $tableNameSuperAttrLabel = $this->helper->getTableName('catalog_product_super_attribute_label');
        $tableNameRelation       = $this->helper->getTableName('catalog_product_relation');
        $tableNameSuperLink      = $this->helper->getTableName('catalog_product_super_link');

        $attributeIds = [];
        $superAttrIds = [];
        $childrenIds  = [];


        /** @var int $position */
        $position = 0;
        /** @var int $id */
        foreach ($this->getConfigurations()->getSuperAttributes() as $associationAttribute) {
            $attributeId = $this->helper->getAttributeIdFromAttributeCode($associationAttribute);
            $attributeIds[$attributeId] = true;

            /** @var array $values */
            $values = [
                'product_id'   => $entityId,
                'attribute_id' => $attributeId,
                'position'     => $position++,
            ];
            $connection->insertOnDuplicate($tableNameSuperAttribute, $values, []);

            /** @var string $superAttributeId */
            $superAttributeId = $connection->fetchOne(
                $connection->select()
                    ->from($tableNameSuperAttribute)
                    ->where('attribute_id = ?', $attributeId)
                    ->where('product_id = ?', $entityId)
            );

            $superAttrIds[] = $superAttributeId;

            foreach (array_keys($stores) as $storeId) {
                $valuesLabels[] = [
                    'product_super_attribute_id' => $superAttributeId,
                    'store_id'                   => $storeId,
                    'use_default'                => 0,
                    'value'                      => '',
                ];
            }
        }


        /** @var array $row */
        foreach($this->getConfigurations()->getAssociatedProducts() as $associatedProduct) {
            /** @var int $childId */
            $childId = (int)$associatedProduct;
            $childrenIds[] = $childId;

            $valuesRelations[] = [
                'parent_id' => $entityId,
                'child_id'  => $childId
            ];

            $valuesSuperLink[] = [
                'product_id' => $childId,
                'parent_id'  => $entityId
            ];
        }

        if($valuesLabels) {
            $connection->insertOnDuplicate(
                $tableNameSuperAttrLabel,
                $valuesLabels,
                []
            );
        }

        if($valuesRelations) {
            $connection->insertOnDuplicate(
                $tableNameRelation,
                $valuesRelations,
                []
            );
        }

        if($valuesSuperLink) {
            $connection->insertOnDuplicate(
                $tableNameSuperLink,
                $valuesSuperLink,
                []
            );
        }

        // Clean up
        $delete = $connection->deleteFromSelect(
            $connection->select()
                ->from($tableNameSuperAttribute)
                ->where('attribute_id NOT IN (?)', array_keys($attributeIds))
                ->where('product_id = ?', $entityId),
            $tableNameSuperAttribute
        );
        $connection->query($delete);

        $delete = $connection->deleteFromSelect(
            $connection->select()
                ->from($tableNameRelation)
                ->where('parent_id = ?', $entityId)
                ->where('child_id NOT IN (?)', $childrenIds),
            $tableNameRelation
        );
        $connection->query($delete);

        $delete = $connection->deleteFromSelect(
            $connection->select()
                ->from($tableNameSuperLink)
                ->where('parent_id = ?', $entityId)
                ->where('product_id NOT IN (?)', $childrenIds),
            $tableNameSuperLink
        );
        $connection->query($delete);
    }

    protected function saveCategories()
    {
        if ($this->getCategories()) {
            $newCategories = $this->getCategories();
            $oldCategories = $this->getCategoryIds();

            $categoriesToAdd    = array_diff($newCategories, $oldCategories);
            if ($categoriesToAdd) {
                $this->addToCategories($categoriesToAdd);
            }

            $categoriesToRemove = array_diff($oldCategories, $newCategories);
            if ($categoriesToRemove) {
                $this->removeFromCategories($categoriesToRemove);
            }
        }
    }

    protected function removeFromCategories($categoryIds)
    {
        $connection = $this->helper->getConnection();
        $select = $connection->select()
            ->from('catalog_category_product')
            ->where('product_id = ?', $this->getMagentoId())
            ->where('category_id IN (?)', $categoryIds);
        $query = $connection->deleteFromSelect($select, 'catalog_category_product');
        return $connection->query($query);
    }

    protected function addToCategories($categoryIds)
    {
        $connection = $this->helper->getConnection();
        $productId = $this->getMagentoId();
        $data = array_map(function ($categoryId) use ($productId) {
            return [
                'product_id' => $productId,
                'category_id' => $categoryId
            ];
        }, $categoryIds);
        return $connection->insertOnDuplicate('catalog_category_product', $data);
    }

    protected function getCategoryIds()
    {
        $connection = $this->helper->getConnection();
        $tableName = $this->helper->getTableName('catalog_category_product');
        return $connection->fetchCol(
            $connection->select()
                ->from($tableName, 'category_id')
                ->where('product_id = ?', $this->getMagentoId())
        );
    }

    protected function saveInventory()
    {
        $connection = $this->helper->getConnection();

        $qty = $this->getInventory()->getQty();
        $isInStock = $this->getInventory()->getIsInStock();

        $data = [
            'product_id'                => $this->getMagentoId(),
            'stock_id'                  => 1,
            'qty'                       => $qty,
            'is_in_stock'               => $isInStock,
            'low_stock_date'            => null,
            'stock_status_changed_auto' => 0,
            'website_id'                => 0
        ];

        return $connection->insertOnDuplicate(
            $this->helper->getTableName('cataloginventory_stock_item'),
            $data
        );
    }

    protected function savePricing()
    {
        $pricing = $this->getPricing();

        $price = $pricing->getPrice();
        $specialPrice = $pricing->getSpecialPrice();
        $cost = $pricing->getCost();
        $msrp = $pricing->getMsrp();
        $specialFromDate = $pricing->getSpecialFromDate();
        $specialToDate = $pricing->getSpecialToDate();

        $this->doSaveAttribute('price', $price);
        $this->doSaveAttribute('special_price', $specialPrice);
        $this->doSaveAttribute('special_from_date', $specialFromDate);
        $this->doSaveAttribute('special_to_date', $specialToDate);
        $this->doSaveAttribute('cost', $cost, 0, !$this->getPricing()->getCostWasProvided());
        $this->doSaveAttribute('msrp', $msrp, 0, !$this->getPricing()->getMsrpWasProvided());

        // TODO: Do something with $pricing->getGroupPrices()
    }

    protected function saveWebsites()
    {
        $websiteCodes = $this->getWebsites();
        $connection = $this->helper->getConnection();
        $websiteTableName = $this->helper->getTableName('store_website');
        $productWebsiteTableName = $this->helper->getTableName('catalog_product_website');
        $select = $connection->select()->from($websiteTableName)->columns('website_id');

        if (is_array($websiteCodes)) {
            $select->where('code in (?)', [$websiteCodes]);
        }

        $productId = $this->getMagentoId();
        $websiteIds = $connection->fetchCol($select);

        $connection->delete($productWebsiteTableName, 'product_id = ' . $this->getMagentoId());

        $data = array_map(function ($websiteId) use ($productId) {
            return [
                'product_id' => $productId,
                'website_id' => $websiteId
            ];
        }, $websiteIds);

        return $connection->insertMultiple($productWebsiteTableName, $data);
    }

    protected function saveImages()
    {
        $connection = $this->helper->getConnection();
        $attributeId = $this->helper->getAttributeIdFromAttributeCode('media_gallery');
        $mediaGalleryTableName = $this->helper->getTableName('catalog_product_entity_media_gallery');
        $mediaGalleryValueTableName = $this->helper->getTableName('catalog_product_entity_media_gallery_value');
        $valueToEntityTableName = $this->helper->getTableName('catalog_product_entity_media_gallery_value_to_entity');
        $valueIds = [];
        foreach ($this->getImages() as $index => $image) {
            $imageUrl = $image->getUrl();

            $this->logger->debug("Will save image '$imageUrl' at" . date('H:i:s'));
            try {
                $downloadedFile = $this->helper->saveProductImage($imageUrl);
            } catch (\Exception $ex) {
                throw new ValidationException('Could not download product image: ' . $ex->getMessage());
            }
            $altTexts = $image->getAlternateText();
            $hidden = $image->getHidden();
            $roles = $image->getRoles();

            $valueId = $connection->fetchOne(
                $connection->select()->from($mediaGalleryTableName, ['value_id'])->where('value = ?', $downloadedFile)
            );

            if (!$valueId) {
                $valueId = $connection->fetchOne(
                    $connection->select()->from($mediaGalleryTableName, [new Expr('COALESCE(MAX(`value_id`), 0) + 1')])
                );
            }

            $mediaGalleryData = [
                'value_id'     => $valueId,
                'attribute_id' => $attributeId,
                'value'        => $downloadedFile,
                'media_type'   => 'image',
                'disabled'     => 0
            ];
            $connection->insertOnDuplicate($mediaGalleryTableName, $mediaGalleryData);

            $valueIds[] = $valueId;

            $mediaGalleryValueToEntityData = [
                'value_id'     => $valueId,
                'entity_id' => $this->getMagentoId()
            ];

            $connection->insertOnDuplicate($valueToEntityTableName, $mediaGalleryValueToEntityData, []);

            foreach ($altTexts as $altText) {
                $storeId = $this->helper->scopeCodeToStoreId($altText->getScope());

                $select = $connection->select()
                    ->from($mediaGalleryValueTableName)
                    ->where('value_id', $valueId)
                    ->where('store_id', $storeId)
                    ->where('entity_id', $this->getMagentoId());
                $delete = $connection->deleteFromSelect($select, $mediaGalleryValueTableName);
                $connection->query($delete);

                $label = $altText->getValue();
                $mediaGalleryValueData = [
                    'value_id'  => $valueId,
                    'store_id'  => $storeId,
                    'entity_id' => $this->getMagentoId(),
                    'label'     => $label,
                    'position'  => $index,
                    'disabled'  => $hidden
                ];
                $connection->insertOnDuplicate($mediaGalleryValueTableName, $mediaGalleryValueData);
            }

            foreach ($roles as $role) {
                $this->doSaveAttribute($role, $downloadedFile);
            }
        }

        $results = $connection->fetchCol(
            $connection->select()
                ->from($valueToEntityTableName, 'value_id')
                ->where('entity_id = ?', $this->getMagentoId())
        );

        $imagesToRemove = array_diff($results, $valueIds);

        $condition = $connection->prepareSqlCondition('value_id', ['in' => $imagesToRemove]);
        $connection->delete($mediaGalleryTableName, $condition);
        $connection->delete($mediaGalleryValueTableName, $condition);
        $connection->delete($valueToEntityTableName, $condition);
    }

    protected function saveAttributes()
    {
        foreach ($this->getAttributes() as $attribute) {
            $attributeCode  = $attribute->getCode();
            $attributeValue = $attribute->getValue();

            $attributeId = $this->helper->getAttributeIdFromAttributeCode($attributeCode);

            foreach ($attributeValue as $scopedValue) {
                $storeId = $this->helper->scopeCodeToStoreId($scopedValue->getScope());
                $this->saveAttributeValue($attributeId, $scopedValue->getValue(), $storeId);
            }
        }
    }

    protected function doSaveAttribute($attributeCode, $attributeValue, $storeId = 0, $silentValidation = false)
    {
        $attributeId = $this->helper->getAttributeIdFromAttributeCode($attributeCode);
        if (!$attributeId) {
            if($silentValidation) {
                return false;
            } else {
                throw new ValidationException('Invalid attribute_code: ' . $attributeCode);
            }
        }
        return $this->saveAttributeValue($attributeId, $attributeValue, $storeId);
    }

    protected function saveAttributeValue($attributeId, $attributeValue, $storeId)
    {
        $connection = $this->helper->getConnection();
        $attributeBackend = $this->getAttributeBackend($attributeId);

        if (in_array($attributeBackend, ['datetime', 'decimal', 'int', 'text', 'varchar'])) {

            $valueTable = 'catalog_product_entity_' . $attributeBackend;

            $identifier = $this->helper->tableHasRowId($this->helper->getTableName($valueTable)) ?
                'row_id' :
                'entity_id';

            $data = [
                'attribute_id' => $attributeId,
                'store_id'     => (int) $storeId,
                $identifier    => $this->getMagentoId(),
                'value'        => $attributeValue
            ];
            return $connection->insertOnDuplicate($valueTable, $data);
        }

        return false;
    }

    protected function getAttributeBackend($attributeId)
    {
        $connection = $this->helper->getConnection();
        $tableName = $this->helper->getTableName('eav_attribute');
        return $this->helper->getConnection()->fetchOne(
            $connection->select()
                ->from($tableName, 'backend_type')
                ->where('attribute_id = ?', $attributeId)
        );
    }

    /**
     * Create product entities
     *
     * @return void
     */
    protected function saveEntity()
    {
        $attributeSetId = $this->getAttributeSet();
        $magentoId = $this->getMagentoId();
        /**
         * @var \Magento\Framework\DB\Adapter\AdapterInterface $connection
         */
        $connection = $this->helper->getConnection();
        /** @var string $table */
        $tableName = $this->helper->getTableName('catalog_product_entity');
        /** @var string $columnIdentifier */
        $tableHasRowId = $this->helper->tableHasRowId($tableName);

        /** @var array $values */
        $values = [
            'entity_id'        => $magentoId ?: null,
            'attribute_set_id' => $attributeSetId,
            'type_id'          => $this->getType(),
            'sku'              => $this->getSku(),
            'has_options'      => new Expr(0),
            'required_options' => new Expr(0),
            'created_at'       => new Expr('now()'),
            'updated_at'       => new Expr('now()')
        ];

        if ($tableHasRowId) {
            $values = array_merge($values, [
                'created_in'       => new Expr(1),
                'updated_in'       => new Expr(\Magento\Staging\Model\VersionManager::MAX_VERSION)
            ]);

            $values['row_id'] = $values['entity_id'];
        }

        $connection->insertOnDuplicate($tableName, $values, array_diff(array_keys($values), [
            'entity_id', 'row_id', 'created_at', 'created_in', 'updated_in'
        ]));

        if (!$magentoId) {
            $magentoId = $connection->lastInsertId($tableName);
        }
        $this->setMagentoId($magentoId);
    }

    public function delete()
    {
        $this->logger->debug('ProductEntity::Delete called');
        $magentoId = $this->getMagentoId();
        $connection = $this->helper->getConnection();
        $tableName = $this->helper->getTableName('catalog_product_entity');
        $select = $connection->select()->from($tableName)->where('entity_id = ?', $magentoId);
        $delete = $connection->deleteFromSelect($select, $tableName);
        $this->logger->debug('ProductEntity::Delete will delete');
        $result = $connection->query($delete);
        $this->logger->debug('ProductEntity::Delete deleted');
        return $result;
    }
}
