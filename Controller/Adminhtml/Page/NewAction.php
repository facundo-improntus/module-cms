<?php
/**
 *
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Page;

use PassKeeper\Framework\App\Action\HttpGetActionInterface;

/**
 * Create CMS page action.
 */
class NewAction extends \PassKeeper\Backend\App\Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PassKeeper_Cms::save';

    /**
     * @var \PassKeeper\Backend\Model\View\Result\Forward
     */
    protected $resultForwardFactory;

    /**
     * @param \PassKeeper\Backend\App\Action\Context $context
     * @param \PassKeeper\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(
        \PassKeeper\Backend\App\Action\Context $context,
        \PassKeeper\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    /**
     * Forward to edit
     *
     * @return \PassKeeper\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        /** @var \PassKeeper\Backend\Model\View\Result\Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
