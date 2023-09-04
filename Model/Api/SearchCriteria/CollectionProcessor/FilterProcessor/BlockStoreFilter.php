<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Model\Api\SearchCriteria\CollectionProcessor\FilterProcessor;

use PassKeeper\Framework\Api\Filter;
use PassKeeper\Framework\Api\SearchCriteria\CollectionProcessor\FilterProcessor\CustomFilterInterface;
use PassKeeper\Framework\Data\Collection\AbstractDb;

class BlockStoreFilter implements CustomFilterInterface
{
    /**
     * Apply custom store filter to collection
     *
     * @param Filter $filter
     * @param AbstractDb $collection
     * @return bool
     */
    public function apply(Filter $filter, AbstractDb $collection)
    {
        /** @var \PassKeeper\Cms\Model\ResourceModel\Block\Collection $collection */
        $collection->addStoreFilter($filter->getValue(), false);

        return true;
    }
}
