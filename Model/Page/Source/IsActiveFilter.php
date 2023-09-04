<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Model\Page\Source;

/**
 * Is active filter source
 */
class IsActiveFilter extends IsActive
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return array_merge([['label' => '', 'value' => '']], parent::toOptionArray());
    }
}
