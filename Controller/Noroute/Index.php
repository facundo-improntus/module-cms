<?php
/**
 *
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Noroute;

/**
 * @SuppressWarnings(PHPMD.AllPurposeAction)
 */
class Index extends \PassKeeper\Framework\App\Action\Action
{
    /**
     * @var \PassKeeper\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @param \PassKeeper\Framework\App\Action\Context $context
     * @param \PassKeeper\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(
        \PassKeeper\Framework\App\Action\Context $context,
        \PassKeeper\Framework\Controller\Result\ForwardFactory $resultForwardFactory
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    /**
     * Render CMS 404 Not found page
     *
     * @return \PassKeeper\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $pageId = $this->_objectManager->get(
            \PassKeeper\Framework\App\Config\ScopeConfigInterface::class,
            \PassKeeper\Store\Model\ScopeInterface::SCOPE_STORE
        )->getValue(
            \PassKeeper\Cms\Helper\Page::XML_PATH_NO_ROUTE_PAGE,
            \PassKeeper\Store\Model\ScopeInterface::SCOPE_STORE
        );
        /** @var \PassKeeper\Cms\Helper\Page $pageHelper */
        $pageHelper = $this->_objectManager->get(\PassKeeper\Cms\Helper\Page::class);
        $resultPage = $pageHelper->prepareResultPage($this, $pageId);
        if ($resultPage) {
            $resultPage->setStatusHeader(404, '1.1', 'Not Found');
            $resultPage->setHeader('Status', '404 File not found');
            return $resultPage;
        } else {
            /** @var \PassKeeper\Framework\Controller\Result\Forward $resultForward */
            $resultForward = $this->resultForwardFactory->create();
            $resultForward->setController('index');
            $resultForward->forward('defaultNoRoute');
            return $resultForward;
        }
    }
}
