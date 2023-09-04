<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Block;

use PassKeeper\Framework\App\Action\HttpGetActionInterface;

/**
 * Index action.
 */
class Index extends \PassKeeper\Cms\Controller\Adminhtml\Block implements HttpGetActionInterface
{
    /**
     * @var \PassKeeper\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \PassKeeper\Backend\App\Action\Context $context
     * @param \PassKeeper\Framework\Registry $coreRegistry
     * @param \PassKeeper\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \PassKeeper\Backend\App\Action\Context $context,
        \PassKeeper\Framework\Registry $coreRegistry,
        \PassKeeper\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Index action
     *
     * @return \PassKeeper\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \PassKeeper\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->getConfig()->getTitle()->prepend(__('Blocks'));

        $dataPersistor = $this->_objectManager->get(\PassKeeper\Framework\App\Request\DataPersistorInterface::class);
        $dataPersistor->clear('cms_block');

        return $resultPage;
    }
}
