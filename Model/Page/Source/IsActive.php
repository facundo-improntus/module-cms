<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Model\Page\Source;

use PassKeeper\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \PassKeeper\Cms\Model\Page
     */
    protected $cmsPage;

    /**
     * Constructor
     *
     * @param \PassKeeper\Cms\Model\Page $cmsPage
     */
    public function __construct(\PassKeeper\Cms\Model\Page $cmsPage)
    {
        $this->cmsPage = $cmsPage;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->cmsPage->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
