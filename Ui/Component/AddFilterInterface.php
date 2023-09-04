<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace PassKeeper\Cms\Ui\Component;

use PassKeeper\Framework\Api\Filter;
use PassKeeper\Framework\Api\Search\SearchCriteriaBuilder;

/**
 * Provides extension point to add additional filters to search criteria.
 *
 * @api
 */
interface AddFilterInterface
{
    /**
     * Adds custom filter to search criteria builder based on received filter.
     *
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Filter $filter
     * @return void
     */
    public function addFilter(SearchCriteriaBuilder $searchCriteriaBuilder, Filter $filter);
}
