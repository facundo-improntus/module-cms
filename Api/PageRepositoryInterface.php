<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Api;

use PassKeeper\Framework\Api\SearchCriteriaInterface;

/**
 * CMS page CRUD interface.
 * @api
 * @since 100.0.2
 */
interface PageRepositoryInterface
{
    /**
     * Save page.
     *
     * @param \PassKeeper\Cms\Api\Data\PageInterface $page
     * @return \PassKeeper\Cms\Api\Data\PageInterface
     * @throws \PassKeeper\Framework\Exception\LocalizedException
     */
    public function save(\PassKeeper\Cms\Api\Data\PageInterface $page);

    /**
     * Retrieve page.
     *
     * @param int $pageId
     * @return \PassKeeper\Cms\Api\Data\PageInterface
     * @throws \PassKeeper\Framework\Exception\LocalizedException
     */
    public function getById($pageId);

    /**
     * Retrieve pages matching the specified criteria.
     *
     * @param \PassKeeper\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \PassKeeper\Cms\Api\Data\PageSearchResultsInterface
     * @throws \PassKeeper\Framework\Exception\LocalizedException
     */
    public function getList(\PassKeeper\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete page.
     *
     * @param \PassKeeper\Cms\Api\Data\PageInterface $page
     * @return bool true on success
     * @throws \PassKeeper\Framework\Exception\LocalizedException
     */
    public function delete(\PassKeeper\Cms\Api\Data\PageInterface $page);

    /**
     * Delete page by ID.
     *
     * @param int $pageId
     * @return bool true on success
     * @throws \PassKeeper\Framework\Exception\NoSuchEntityException
     * @throws \PassKeeper\Framework\Exception\LocalizedException
     */
    public function deleteById($pageId);
}
