<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Page;

use PassKeeper\Framework\App\Action\HttpPostActionInterface;
use PassKeeper\Framework\Controller\ResultFactory;
use PassKeeper\Backend\App\Action\Context;
use PassKeeper\Ui\Component\MassAction\Filter;
use PassKeeper\Cms\Model\ResourceModel\Page\CollectionFactory;

/**
 * Class MassDisable
 */
class MassDisable extends \PassKeeper\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PassKeeper_Cms::save';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \PassKeeper\Backend\Model\View\Result\Redirect
     * @throws \PassKeeper\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        foreach ($collection as $item) {
            $item->setIsActive(false);
            $item->save();
        }

        $this->messageManager->addSuccessMessage(
            __('A total of %1 record(s) have been disabled.', $collection->getSize())
        );

        /** @var \PassKeeper\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
