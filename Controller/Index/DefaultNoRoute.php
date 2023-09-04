<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Index;

class DefaultNoRoute extends \PassKeeper\Framework\App\Action\Action
{
    /**
     * @var \PassKeeper\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \PassKeeper\Framework\App\Action\Context $context
     * @param \PassKeeper\Framework\View\Result\PageFactory resultPageFactory
     */
    public function __construct(
        \PassKeeper\Framework\App\Action\Context $context,
        \PassKeeper\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return \PassKeeper\Framework\View\Result\LayoutFactory
     */
    public function execute()
    {
        $resultLayout = $this->resultPageFactory->create();
        $resultLayout->setStatusHeader(404, '1.1', 'Not Found');
        $resultLayout->setHeader('Status', '404 File not found');
        return $resultLayout;
    }
}
