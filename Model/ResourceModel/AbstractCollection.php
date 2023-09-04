<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Model\ResourceModel;

use PassKeeper\Store\Model\Store;

/**
 * Abstract collection of CMS pages and blocks
 */
abstract class AbstractCollection extends \PassKeeper\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Store manager
     *
     * @var \PassKeeper\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \PassKeeper\Framework\EntityManager\MetadataPool
     */
    protected $metadataPool;

    /**
     * @param \PassKeeper\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \PassKeeper\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \PassKeeper\Framework\Event\ManagerInterface $eventManager
     * @param \PassKeeper\Store\Model\StoreManagerInterface $storeManager
     * @param \PassKeeper\Framework\EntityManager\MetadataPool $metadataPool
     * @param \PassKeeper\Framework\DB\Adapter\AdapterInterface|null $connection
     * @param \PassKeeper\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     */
    public function __construct(
        \PassKeeper\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \PassKeeper\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \PassKeeper\Framework\Event\ManagerInterface $eventManager,
        \PassKeeper\Store\Model\StoreManagerInterface $storeManager,
        \PassKeeper\Framework\EntityManager\MetadataPool $metadataPool,
        \PassKeeper\Framework\DB\Adapter\AdapterInterface $connection = null,
        \PassKeeper\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->storeManager = $storeManager;
        $this->metadataPool = $metadataPool;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * Perform operations after collection load
     *
     * @param string $tableName
     * @param string|null $linkField
     * @return void
     */
    protected function performAfterLoad($tableName, $linkField)
    {
        $linkedIds = $this->getColumnValues($linkField);
        if (count($linkedIds)) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(['cms_entity_store' => $this->getTable($tableName)])
                ->where('cms_entity_store.' . $linkField . ' IN (?)', $linkedIds);
            $result = $connection->fetchAll($select);
            if ($result) {
                $storesData = [];
                foreach ($result as $storeData) {
                    $storesData[$storeData[$linkField]][] = $storeData['store_id'];
                }

                foreach ($this as $item) {
                    $linkedId = $item->getData($linkField);
                    if (!isset($storesData[$linkedId])) {
                        continue;
                    }
                    $storeIdKey = array_search(Store::DEFAULT_STORE_ID, $storesData[$linkedId], true);
                    if ($storeIdKey !== false) {
                        $stores = $this->storeManager->getStores(false, true);
                        $storeId = current($stores)->getId();
                        $storeCode = key($stores);
                    } else {
                        $storeId = current($storesData[$linkedId]);
                        $storeCode = $this->storeManager->getStore($storeId)->getCode();
                    }
                    $item->setData('_first_store_id', $storeId);
                    $item->setData('store_code', $storeCode);
                    $item->setData('store_id', $storesData[$linkedId]);
                }
            }
        }
    }

    /**
     * Add field filter to collection
     *
     * @param array|string $field
     * @param string|int|array|null $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'store_id') {
            return $this->addStoreFilter($condition, false);
        }

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Add filter by store
     *
     * @param int|array|Store $store
     * @param bool $withAdmin
     * @return $this
     */
    abstract public function addStoreFilter($store, $withAdmin = true);

    /**
     * Perform adding filter by store
     *
     * @param int|array|Store $store
     * @param bool $withAdmin
     * @return void
     */
    protected function performAddStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof Store) {
            $store = [$store->getId()];
        }

        if (!is_array($store)) {
            $store = [$store];
        }

        if ($withAdmin) {
            $store[] = Store::DEFAULT_STORE_ID;
        }

        $this->addFilter('store', ['in' => $store], 'public');
    }

    /**
     * Join store relation table if there is store filter
     *
     * @param string $tableName
     * @param string|null $linkField
     * @return void
     */
    protected function joinStoreRelationTable($tableName, $linkField)
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                ['store_table' => $this->getTable($tableName)],
                'main_table.' . $linkField . ' = store_table.' . $linkField,
                []
            )->group(
                'main_table.' . $linkField
            );
        }
        parent::_renderFiltersBefore();
    }

    /**
     * Get SQL for get record count
     *
     * Extra GROUP BY strip added.
     *
     * @return \PassKeeper\Framework\DB\Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(\PassKeeper\Framework\DB\Select::GROUP);

        return $countSelect;
    }

    /**
     * Returns pairs identifier - title for unique identifiers
     * and pairs identifier|entity_id - title for non-unique after first
     *
     * @return array
     */
    public function toOptionIdArray()
    {
        $res = [];
        $existingIdentifiers = [];
        foreach ($this as $item) {
            $identifier = $item->getData('identifier');

            $data['value'] = $identifier;
            $data['label'] = $item->getData('title');

            if (in_array($identifier, $existingIdentifiers)) {
                $data['value'] .= '|' . $item->getData($this->getIdFieldName());
            } else {
                $existingIdentifiers[] = $identifier;
            }

            $res[] = $data;
        }

        return $res;
    }
}
