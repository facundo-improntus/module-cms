<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace PassKeeper\Cms\Block;

use PassKeeper\Framework\View\Element\AbstractBlock;

/**
 * Cms block content block
 * @deprecated This class introduces caching issues and should no longer be used
 * @see \PassKeeper\Cms\Block\BlockByIdentifier
 */
class Block extends AbstractBlock implements \PassKeeper\Framework\DataObject\IdentityInterface
{
    /**
     * Prefix for cache key of CMS block
     */
    const CACHE_KEY_PREFIX = 'CMS_BLOCK_';

    /**
     * @var \PassKeeper\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * Store manager
     *
     * @var \PassKeeper\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Block factory
     *
     * @var \PassKeeper\Cms\Model\BlockFactory
     */
    protected $_blockFactory;

    /**
     * Construct
     *
     * @param \PassKeeper\Framework\View\Element\Context $context
     * @param \PassKeeper\Cms\Model\Template\FilterProvider $filterProvider
     * @param \PassKeeper\Store\Model\StoreManagerInterface $storeManager
     * @param \PassKeeper\Cms\Model\BlockFactory $blockFactory
     * @param array $data
     */
    public function __construct(
        \PassKeeper\Framework\View\Element\Context $context,
        \PassKeeper\Cms\Model\Template\FilterProvider $filterProvider,
        \PassKeeper\Store\Model\StoreManagerInterface $storeManager,
        \PassKeeper\Cms\Model\BlockFactory $blockFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_filterProvider = $filterProvider;
        $this->_storeManager = $storeManager;
        $this->_blockFactory = $blockFactory;
    }

    /**
     * Prepare Content HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        $blockId = $this->getBlockId();
        $html = '';
        if ($blockId) {
            $storeId = $this->_storeManager->getStore()->getId();
            /** @var \PassKeeper\Cms\Model\Block $block */
            $block = $this->_blockFactory->create();
            $block->setStoreId($storeId)->load($blockId);
            if ($block->isActive()) {
                $html = $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($block->getContent());
            }
        }
        return $html;
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\PassKeeper\Cms\Model\Block::CACHE_TAG . '_' . $this->getBlockId()];
    }

    /**
     * @inheritdoc
     */
    public function getCacheKeyInfo()
    {
        $cacheKeyInfo = parent::getCacheKeyInfo();
        $cacheKeyInfo[] = $this->_storeManager->getStore()->getId();
        return $cacheKeyInfo;
    }
}
