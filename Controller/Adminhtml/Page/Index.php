<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Page;

use PassKeeper\Framework\App\Action\HttpGetActionInterface;
use PassKeeper\Backend\App\Action\Context;
use PassKeeper\Framework\App\ObjectManager;
use PassKeeper\Framework\App\Request\DataPersistorInterface;
use PassKeeper\Framework\View\Result\PageFactory;

/**
 * Index action.
 */
class Index extends \PassKeeper\Backend\App\Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PassKeeper_Cms::page';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        DataPersistorInterface $dataPersistor = null
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->dataPersistor = $dataPersistor ?: ObjectManager::getInstance()->get(DataPersistorInterface::class);
    }

    /**
     * Index action
     *
     * @return \PassKeeper\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \PassKeeper\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('PassKeeper_Cms::cms_page');
        $resultPage->addBreadcrumb(__('CMS'), __('CMS'));
        $resultPage->addBreadcrumb(__('Manage Pages'), __('Manage Pages'));
        $resultPage->getConfig()->getTitle()->prepend(__('Pages'));

        $this->dataPersistor->clear('cms_page');

        return $resultPage;
    }
}
