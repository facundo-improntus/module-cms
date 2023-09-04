<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Model\ResourceModel\Block\Grid;

use PassKeeper\Framework\Api\Search\SearchResultInterface;
use PassKeeper\Framework\Api\Search\AggregationInterface;
use PassKeeper\Cms\Model\ResourceModel\Block\Collection as BlockCollection;
use PassKeeper\Framework\App\ObjectManager;
use PassKeeper\Framework\Data\Collection\Db\FetchStrategyInterface;
use PassKeeper\Framework\Data\Collection\EntityFactoryInterface;
use PassKeeper\Framework\DB\Adapter\AdapterInterface;
use PassKeeper\Framework\EntityManager\MetadataPool;
use PassKeeper\Framework\Event\ManagerInterface;
use PassKeeper\Framework\Model\ResourceModel\Db\AbstractDb;
use PassKeeper\Framework\Stdlib\DateTime\TimezoneInterface;
use PassKeeper\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Collection for displaying grid of cms blocks
 */
class Collection extends BlockCollection implements SearchResultInterface
{
    /**
     * @var TimezoneInterface
     */
    private $timeZone;

    /**
     * @var AggregationInterface
     */
    protected $aggregations;

    /**
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param StoreManagerInterface $storeManager
     * @param MetadataPool $metadataPool
     * @param string $mainTable
     * @param string $eventPrefix
     * @param string $eventObject
     * @param string $resourceModel
     * @param string $model
     * @param AdapterInterface|string|null $connection
     * @param AbstractDb $resource
     * @param TimezoneInterface|null $timeZone
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        MetadataPool $metadataPool,
        $mainTable,
        $eventPrefix,
        $eventObject,
        $resourceModel,
        $model = \PassKeeper\Framework\View\Element\UiComponent\DataProvider\Document::class,
        $connection = null,
        AbstractDb $resource = null,
        TimezoneInterface $timeZone = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $storeManager,
            $metadataPool,
            $connection,
            $resource
        );
        $this->_eventPrefix = $eventPrefix;
        $this->_eventObject = $eventObject;
        $this->_init($model, $resourceModel);
        $this->setMainTable($mainTable);
        $this->timeZone = $timeZone ?: ObjectManager::getInstance()->get(TimezoneInterface::class);
    }

    /**
     * @inheritDoc
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'creation_time' || $field === 'update_time') {
            if (is_array($condition)) {
                foreach ($condition as $key => $value) {
                    $condition[$key] = $this->timeZone->convertConfigTimeToUtc($value);
                }
            }
        }

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Get aggregation interface instance
     *
     * @return AggregationInterface
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * Set aggregation interface instance
     *
     * @param AggregationInterface $aggregations
     * @return $this
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
        return $this;
    }

    /**
     * Get search criteria.
     *
     * @return \PassKeeper\Framework\Api\SearchCriteriaInterface|null
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * Set search criteria.
     *
     * @param \PassKeeper\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setSearchCriteria(\PassKeeper\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }

    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * Set total count.
     *
     * @param int $totalCount
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * Set items list.
     *
     * @param \PassKeeper\Framework\Api\ExtensibleDataInterface[] $items
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setItems(array $items = null)
    {
        return $this;
    }
}
