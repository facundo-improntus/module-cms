<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace PassKeeper\Cms\Model;

use PassKeeper\Cms\Api\BlockRepositoryInterface;
use PassKeeper\Cms\Api\Data;
use PassKeeper\Cms\Model\ResourceModel\Block as ResourceBlock;
use PassKeeper\Cms\Model\ResourceModel\Block\CollectionFactory as BlockCollectionFactory;
use PassKeeper\Framework\Api\DataObjectHelper;
use PassKeeper\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use PassKeeper\Framework\App\ObjectManager;
use PassKeeper\Framework\Exception\CouldNotDeleteException;
use PassKeeper\Framework\Exception\CouldNotSaveException;
use PassKeeper\Framework\Exception\NoSuchEntityException;
use PassKeeper\Framework\Reflection\DataObjectProcessor;
use PassKeeper\Store\Model\StoreManagerInterface;
use PassKeeper\Framework\EntityManager\HydratorInterface;

/**
 * Default block repo impl.
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BlockRepository implements BlockRepositoryInterface
{
    /**
     * @var ResourceBlock
     */
    protected $resource;

    /**
     * @var BlockFactory
     */
    protected $blockFactory;

    /**
     * @var BlockCollectionFactory
     */
    protected $blockCollectionFactory;

    /**
     * @var Data\BlockSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \PassKeeper\Cms\Api\Data\BlockInterfaceFactory
     */
    protected $dataBlockFactory;

    /**
     * @var \PassKeeper\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @param ResourceBlock $resource
     * @param BlockFactory $blockFactory
     * @param Data\BlockInterfaceFactory $dataBlockFactory
     * @param BlockCollectionFactory $blockCollectionFactory
     * @param Data\BlockSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param HydratorInterface|null $hydrator
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        ResourceBlock $resource,
        BlockFactory $blockFactory,
        \PassKeeper\Cms\Api\Data\BlockInterfaceFactory $dataBlockFactory,
        BlockCollectionFactory $blockCollectionFactory,
        Data\BlockSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor = null,
        ?HydratorInterface $hydrator = null
    ) {
        $this->resource = $resource;
        $this->blockFactory = $blockFactory;
        $this->blockCollectionFactory = $blockCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataBlockFactory = $dataBlockFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
        $this->hydrator = $hydrator ?? ObjectManager::getInstance()->get(HydratorInterface::class);
    }

    /**
     * Save Block data
     *
     * @param \PassKeeper\Cms\Api\Data\BlockInterface $block
     * @return Block
     * @throws CouldNotSaveException
     */
    public function save(Data\BlockInterface $block)
    {
        if (empty($block->getStoreId())) {
            $block->setStoreId($this->storeManager->getStore()->getId());
        }

        if ($block->getId() && $block instanceof Block && !$block->getOrigData()) {
            $block = $this->hydrator->hydrate($this->getById($block->getId()), $this->hydrator->extract($block));
        }

        try {
            $this->resource->save($block);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $block;
    }

    /**
     * Load Block data by given Block Identity
     *
     * @param string $blockId
     * @return Block
     * @throws \PassKeeper\Framework\Exception\NoSuchEntityException
     */
    public function getById($blockId)
    {
        $block = $this->blockFactory->create();
        $this->resource->load($block, $blockId);
        if (!$block->getId()) {
            throw new NoSuchEntityException(__('The CMS block with the "%1" ID doesn\'t exist.', $blockId));
        }
        return $block;
    }

    /**
     * Load Block data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \PassKeeper\Framework\Api\SearchCriteriaInterface $criteria
     * @return \PassKeeper\Cms\Api\Data\BlockSearchResultsInterface
     */
    public function getList(\PassKeeper\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var \PassKeeper\Cms\Model\ResourceModel\Block\Collection $collection */
        $collection = $this->blockCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var Data\BlockSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete Block
     *
     * @param \PassKeeper\Cms\Api\Data\BlockInterface $block
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\BlockInterface $block)
    {
        try {
            $this->resource->delete($block);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Block by given Block Identity
     *
     * @param string $blockId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($blockId)
    {
        return $this->delete($this->getById($blockId));
    }

    /**
     * Retrieve collection processor
     *
     * @deprecated 102.0.0
     * @return CollectionProcessorInterface
     */
    private function getCollectionProcessor()
    {
        //phpcs:disable PassKeeper2.PHP.LiteralNamespaces
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \PassKeeper\Framework\App\ObjectManager::getInstance()->get(
                'PassKeeper\Cms\Model\Api\SearchCriteria\BlockCollectionProcessor'
            );
        }
        return $this->collectionProcessor;
    }
}
