<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace PassKeeper\Cms\Model;

use PassKeeper\Cms\Api\Data\PageSearchResultsInterface;
use PassKeeper\Framework\Api\SearchResults;

/**
 * Service Data Object with Page search results.
 */
class PageSearchResults extends SearchResults implements PageSearchResultsInterface
{
}
