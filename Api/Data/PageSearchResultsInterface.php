<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Api\Data;

use PassKeeper\Framework\Api\SearchResultsInterface;

/**
 * Interface for cms page search results.
 * @api
 * @since 100.0.2
 */
interface PageSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get pages list.
     *
     * @return \PassKeeper\Cms\Api\Data\PageInterface[]
     */
    public function getItems();

    /**
     * Set pages list.
     *
     * @param \PassKeeper\Cms\Api\Data\PageInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
