<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Model\Page\Source;

use PassKeeper\Framework\Data\OptionSourceInterface;
use PassKeeper\Framework\View\Model\PageLayout\Config\BuilderInterface;

/**
 * Class PageLayout
 */
class PageLayout implements OptionSourceInterface
{
    /**
     * @var \PassKeeper\Framework\View\Model\PageLayout\Config\BuilderInterface
     */
    protected $pageLayoutBuilder;

    /**
     * @var array
     * @deprecated 103.0.1 since the cache is now handled by \PassKeeper\Theme\Model\PageLayout\Config\Builder::$configFiles
     */
    protected $options;

    /**
     * Constructor
     *
     * @param BuilderInterface $pageLayoutBuilder
     */
    public function __construct(BuilderInterface $pageLayoutBuilder)
    {
        $this->pageLayoutBuilder = $pageLayoutBuilder;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $configOptions = $this->pageLayoutBuilder->getPageLayoutsConfig()->getOptions();
        $options = [];
        foreach ($configOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        $this->options = $options;

        return $options;
    }
}
