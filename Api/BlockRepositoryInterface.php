<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Api;

/**
 * CMS block CRUD interface.
 * @api
 * @since 100.0.2
 */
interface BlockRepositoryInterface
{
    /**
     * Save block.
     *
     * @param \PassKeeper\Cms\Api\Data\BlockInterface $block
     * @return \PassKeeper\Cms\Api\Data\BlockInterface
     * @throws \PassKeeper\Framework\Exception\LocalizedException
     */
    public function save(Data\BlockInterface $block);

    /**
     * Retrieve block.
     *
     * @param string $blockId
     * @return \PassKeeper\Cms\Api\Data\BlockInterface
     * @throws \PassKeeper\Framework\Exception\LocalizedException
     */
    public function getById($blockId);

    /**
     * Retrieve blocks matching the specified criteria.
     *
     * @param \PassKeeper\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \PassKeeper\Cms\Api\Data\BlockSearchResultsInterface
     * @throws \PassKeeper\Framework\Exception\LocalizedException
     */
    public function getList(\PassKeeper\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete block.
     *
     * @param \PassKeeper\Cms\Api\Data\BlockInterface $block
     * @return bool true on success
     * @throws \PassKeeper\Framework\Exception\LocalizedException
     */
    public function delete(Data\BlockInterface $block);

    /**
     * Delete block by ID.
     *
     * @param string $blockId
     * @return bool true on success
     * @throws \PassKeeper\Framework\Exception\NoSuchEntityException
     * @throws \PassKeeper\Framework\Exception\LocalizedException
     */
    public function deleteById($blockId);
}
