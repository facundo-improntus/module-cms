<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace PassKeeper\Cms\Block;

use PassKeeper\Cms\Api\Data\BlockInterface;
use PassKeeper\Cms\Api\GetBlockByIdentifierInterface;
use PassKeeper\Cms\Model\Block as BlockModel;
use PassKeeper\Cms\Model\Template\FilterProvider;
use PassKeeper\Framework\DataObject\IdentityInterface;
use PassKeeper\Framework\Exception\NoSuchEntityException;
use PassKeeper\Framework\View\Element\AbstractBlock;
use PassKeeper\Framework\View\Element\Context;
use PassKeeper\Store\Model\StoreManagerInterface;

/**
 * This class is replacement of \PassKeeper\Cms\Block\Block, that accepts only `string` identifier of CMS Block
 */
class BlockByIdentifier extends AbstractBlock implements IdentityInterface
{
    public const CACHE_KEY_PREFIX = 'CMS_BLOCK';

    /**
     * @var GetBlockByIdentifierInterface
     */
    private $blockByIdentifier;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var FilterProvider
     */
    private $filterProvider;

    /**
     * @var BlockInterface
     */
    private $cmsBlock;

    /**
     * @param GetBlockByIdentifierInterface $blockByIdentifier
     * @param StoreManagerInterface $storeManager
     * @param FilterProvider $filterProvider
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        GetBlockByIdentifierInterface $blockByIdentifier,
        StoreManagerInterface $storeManager,
        FilterProvider $filterProvider,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->blockByIdentifier = $blockByIdentifier;
        $this->storeManager = $storeManager;
        $this->filterProvider = $filterProvider;
    }

    /**
     * @inheritDoc
     */
    protected function _toHtml(): string
    {
        try {
            return $this->filterOutput(
                $this->getCmsBlock()->getContent()
            );
        } catch (NoSuchEntityException $e) {
            return '';
        }
    }

    /**
     * Returns the value of `identifier` injected in `<block>` definition
     *
     * @return string|null
     */
    private function getIdentifier(): ?string
    {
        return $this->getData('identifier') ?: null;
    }

    /**
     * Filters the Content
     *
     * @param string $content
     * @return string
     * @throws NoSuchEntityException
     */
    private function filterOutput(string $content): string
    {
        return $this->filterProvider->getBlockFilter()
            ->setStoreId($this->getCurrentStoreId())
            ->filter($content);
    }

    /**
     * Loads the CMS block by `identifier` provided as an argument
     *
     * @return BlockInterface|BlockModel
     * @throws \InvalidArgumentException
     * @throws NoSuchEntityException
     */
    private function getCmsBlock(): BlockInterface
    {
        if (!$this->getIdentifier()) {
            throw new \InvalidArgumentException('Expected value of `identifier` was not provided');
        }

        if (null === $this->cmsBlock) {
            $this->cmsBlock = $this->blockByIdentifier->execute(
                (string)$this->getIdentifier(),
                $this->getCurrentStoreId()
            );

            if (!$this->cmsBlock->isActive()) {
                throw new NoSuchEntityException(
                    __('The CMS block with identifier "%identifier" is not enabled.', $this->getIdentifier())
                );
            }
        }

        return $this->cmsBlock;
    }

    /**
     * Returns the current Store ID
     *
     * @return int
     * @throws \PassKeeper\Framework\Exception\NoSuchEntityException
     */
    private function getCurrentStoreId(): int
    {
        return (int)$this->storeManager->getStore()->getId();
    }

    /**
     * Returns array of Block Identifiers used to determine Cache Tags
     *
     * This implementation supports different CMS blocks caching having the same identifier,
     * resolving the bug introduced in scope of \PassKeeper\Cms\Block\Block
     *
     * @return string[]
     */
    public function getIdentities(): array
    {
        if (!$this->getIdentifier()) {
            return [];
        }

        $identities = [
            self::CACHE_KEY_PREFIX . '_' . $this->getIdentifier(),
            self::CACHE_KEY_PREFIX . '_' . $this->getIdentifier() . '_' . $this->getCurrentStoreId()
        ];

        try {
            $cmsBlock = $this->getCmsBlock();
            if ($cmsBlock instanceof IdentityInterface) {
                $identities = array_merge($identities, $cmsBlock->getIdentities());
            }
            // phpcs:ignore PassKeeper2.CodeAnalysis.EmptyBlock.DetectedCatch
        } catch (NoSuchEntityException $e) {
        }

        return $identities;
    }
}
