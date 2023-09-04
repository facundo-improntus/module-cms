<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml;

abstract class Block extends \PassKeeper\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PassKeeper_Cms::block';

    /**
     * Core registry
     *
     * @var \PassKeeper\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @param \PassKeeper\Backend\App\Action\Context $context
     * @param \PassKeeper\Framework\Registry $coreRegistry
     */
    public function __construct(\PassKeeper\Backend\App\Action\Context $context, \PassKeeper\Framework\Registry $coreRegistry)
    {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \PassKeeper\Backend\Model\View\Result\Page $resultPage
     * @return \PassKeeper\Backend\Model\View\Result\Page
     */
    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('PassKeeper_Cms::cms_block')
            ->addBreadcrumb(__('CMS'), __('CMS'))
            ->addBreadcrumb(__('Static Blocks'), __('Static Blocks'));
        return $resultPage;
    }
}
