<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Model\Config\Source\Wysiwyg;

/**
 * Configuration source model for Wysiwyg toggling
 */
class Enabled implements \PassKeeper\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            ['value' => \PassKeeper\Cms\Model\Wysiwyg\Config::WYSIWYG_ENABLED, 'label' => __('Enabled by Default')],
            ['value' => \PassKeeper\Cms\Model\Wysiwyg\Config::WYSIWYG_HIDDEN, 'label' => __('Disabled by Default')],
            ['value' => \PassKeeper\Cms\Model\Wysiwyg\Config::WYSIWYG_DISABLED, 'label' => __('Disabled Completely')]
        ];
    }
}
