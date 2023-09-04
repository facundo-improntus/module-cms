<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Model\Page\Source;

use PassKeeper\Framework\Data\OptionSourceInterface;
use PassKeeper\Framework\View\Design\Theme\Label\ListInterface;

/**
 * Class Theme
 */
class Theme implements OptionSourceInterface
{
    /**
     * @var \PassKeeper\Framework\View\Design\Theme\Label\ListInterface
     */
    protected $themeList;

    /**
     * Constructor
     *
     * @param ListInterface $themeList
     */
    public function __construct(ListInterface $themeList)
    {
        $this->themeList = $themeList;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => 'Default', 'value' => ''];
        return array_merge($options, $this->themeList->getLabels());
    }
}
