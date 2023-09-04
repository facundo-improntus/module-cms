<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Page;

use PassKeeper\Framework\App\Action\HttpGetActionInterface;
use PassKeeper\Backend\App\Action;

/**
 * Edit CMS page action.
 */
class Edit extends \PassKeeper\Backend\App\Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PassKeeper_Cms::save';

    /**
     * Core registry
     *
     * @var \PassKeeper\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \PassKeeper\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param \PassKeeper\Framework\View\Result\PageFactory $resultPageFactory
     * @param \PassKeeper\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \PassKeeper\Framework\View\Result\PageFactory $resultPageFactory,
        \PassKeeper\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return \PassKeeper\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \PassKeeper\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('PassKeeper_Cms::cms_page')
            ->addBreadcrumb(__('CMS'), __('CMS'))
            ->addBreadcrumb(__('Manage Pages'), __('Manage Pages'));
        return $resultPage;
    }

    /**
     * Edit CMS page
     *
     * @return \PassKeeper\Backend\Model\View\Result\Page|\PassKeeper\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('page_id');
        $model = $this->_objectManager->create(\PassKeeper\Cms\Model\Page::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This page no longer exists.'));
                /** \PassKeeper\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('cms_page', $model);

        // 5. Build edit form
        /** @var \PassKeeper\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Page') : __('New Page'),
            $id ? __('Edit Page') : __('New Page')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Pages'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Page'));

        return $resultPage;
    }
}
