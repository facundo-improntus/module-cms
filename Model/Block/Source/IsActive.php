<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Model\Block\Source;

use PassKeeper\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \PassKeeper\Cms\Model\Block
     */
    protected $cmsBlock;

    /**
     * Constructor
     *
     * @param \PassKeeper\Cms\Model\Block $cmsBlock
     */
    public function __construct(\PassKeeper\Cms\Model\Block $cmsBlock)
    {
        $this->cmsBlock = $cmsBlock;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->cmsBlock->getAvailableStatuses();
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
