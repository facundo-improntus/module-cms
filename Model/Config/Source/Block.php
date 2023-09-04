<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace PassKeeper\Cms\Model\Config\Source;

use PassKeeper\Cms\Model\ResourceModel\Block\CollectionFactory;
use PassKeeper\Framework\Data\OptionSourceInterface;

/**
 * Class Block
 */
class Block implements OptionSourceInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var \PassKeeper\Cms\Model\ResourceModel\Block\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param \PassKeeper\Cms\Model\ResourceModel\Block\CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = $this->collectionFactory->create()->toOptionIdArray();
        }

        return $this->options;
    }
}
