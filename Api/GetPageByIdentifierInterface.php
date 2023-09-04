<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Api;

/**
 * Command to load the page data by specified identifier
 * @api
 * @since 103.0.0
 */
interface GetPageByIdentifierInterface
{
    /**
     * Load page data by given page identifier.
     *
     * @param string $identifier
     * @param int $storeId
     * @throws \PassKeeper\Framework\Exception\NoSuchEntityException
     * @return \PassKeeper\Cms\Api\Data\PageInterface
     * @since 103.0.0
     */
    public function execute(string $identifier, int $storeId) : \PassKeeper\Cms\Api\Data\PageInterface;
}
