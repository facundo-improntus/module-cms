<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace PassKeeper\Cms\Model;

use PassKeeper\Cms\Api\Data\PageInterface;
use PassKeeper\Cms\Api\GetPageByIdentifierInterface;
use PassKeeper\Framework\Exception\NoSuchEntityException;

/**
 * Class GetPageByIdentifier
 */
class GetPageByIdentifier implements GetPageByIdentifierInterface
{
    /**
     * @var \PassKeeper\Cms\Model\PageFactory
     */
    private $pageFactory;

    /**
     * @var ResourceModel\Page
     */
    private $pageResource;

    /**
     * @param PageFactory $pageFactory
     * @param ResourceModel\Page $pageResource
     */
    public function __construct(
        \PassKeeper\Cms\Model\PageFactory $pageFactory,
        \PassKeeper\Cms\Model\ResourceModel\Page $pageResource
    ) {
        $this->pageFactory = $pageFactory;
        $this->pageResource = $pageResource;
    }

    /**
     * @inheritdoc
     */
    public function execute(string $identifier, int $storeId) : PageInterface
    {
        $page = $this->pageFactory->create();
        $page->setStoreId($storeId);
        $this->pageResource->load($page, $identifier, PageInterface::IDENTIFIER);

        if (!$page->getId()) {
            throw new NoSuchEntityException(__('The CMS page with the "%1" ID doesn\'t exist.', $identifier));
        }

        return $page;
    }
}
