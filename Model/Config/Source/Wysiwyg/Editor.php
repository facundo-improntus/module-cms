<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Model\Config\Source\Wysiwyg;

/**
 * Configuration source model for Wysiwyg toggling
 */
class Editor implements \PassKeeper\Framework\Option\ArrayInterface
{
    /**
     * @var array
     */
    private $adapterOptions;

    /**
     * @param array $adapterOptions
     */
    public function __construct(array $adapterOptions = [])
    {
        $this->adapterOptions = $adapterOptions;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return $this->adapterOptions;
    }
}
