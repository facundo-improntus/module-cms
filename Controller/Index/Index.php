<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Index;

use PassKeeper\Framework\App\Action\HttpPostActionInterface;
use PassKeeper\Framework\App\Action\HttpGetActionInterface;
use PassKeeper\Framework\App\Action\Context;
use PassKeeper\Framework\App\Config\ScopeConfigInterface;
use PassKeeper\Framework\App\ObjectManager;
use PassKeeper\Framework\App\ResponseInterface;
use PassKeeper\Framework\Controller\ResultInterface;
use PassKeeper\Framework\Controller\Result\Forward;
use PassKeeper\Framework\Controller\Result\ForwardFactory;
use PassKeeper\Framework\View\Result\Page as ResultPage;
use PassKeeper\Cms\Helper\Page;
use PassKeeper\Store\Model\ScopeInterface;
use PassKeeper\Framework\App\Action\Action;

/**
 * Home page. Needs to be accessible by POST because of the store switching.
 */
class Index extends Action implements HttpGetActionInterface, HttpPostActionInterface
{
    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Page
     */
    private $page;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param ForwardFactory $resultForwardFactory
     * @param ScopeConfigInterface|null $scopeConfig
     * @param Page|null $page
     */
    public function __construct(
        Context $context,
        ForwardFactory $resultForwardFactory,
        ScopeConfigInterface $scopeConfig = null,
        Page $page = null
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->scopeConfig = $scopeConfig ? : ObjectManager::getInstance()->get(ScopeConfigInterface::class);
        $this->page = $page ? : ObjectManager::getInstance()->get(Page::class);
        parent::__construct($context);
    }

    /**
     * Renders CMS Home page
     *
     * @param string|null $coreRoute
     *
     * @return bool|ResponseInterface|Forward|ResultInterface|ResultPage
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($coreRoute = null)
    {
        $pageId = $this->scopeConfig->getValue(Page::XML_PATH_HOME_PAGE, ScopeInterface::SCOPE_STORE);
        $resultPage = $this->page->prepareResultPage($this, $pageId);
        if (!$resultPage) {
            /** @var Forward $resultForward */
            $resultForward = $this->resultForwardFactory->create();
            $resultForward->forward('defaultIndex');
            return $resultForward;
        }
        return $resultPage;
    }
}
