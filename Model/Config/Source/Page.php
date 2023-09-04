<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Model\Config\Source;

use PassKeeper\Cms\Model\ResourceModel\Page\CollectionFactory;

/**
 * Class Page
 */
class Page implements \PassKeeper\Framework\Option\ArrayInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * To option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = $this->collectionFactory->create()->toOptionIdArray();
        }
        return $this->options;
    }
}
