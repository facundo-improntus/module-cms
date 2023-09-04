<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace PassKeeper\Cms\Model;

use PassKeeper\Cms\Api\Data\BlockSearchResultsInterface;
use PassKeeper\Framework\Api\SearchResults;

/**
 * Service Data Object with Block search results.
 */
class BlockSearchResults extends SearchResults implements BlockSearchResultsInterface
{
}
