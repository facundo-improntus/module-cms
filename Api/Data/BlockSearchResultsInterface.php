<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Api\Data;

use PassKeeper\Framework\Api\SearchResultsInterface;

/**
 * Interface for cms block search results.
 * @api
 * @since 100.0.2
 */
interface BlockSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get blocks list.
     *
     * @return \PassKeeper\Cms\Api\Data\BlockInterface[]
     */
    public function getItems();

    /**
     * Set blocks list.
     *
     * @param \PassKeeper\Cms\Api\Data\BlockInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
