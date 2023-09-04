<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Router implements \PassKeeper\Framework\App\RouterInterface
{
    /**
     * @var \PassKeeper\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * Event manager
     *
     * @var \PassKeeper\Framework\Event\ManagerInterface
     */
    protected $_eventManager;

    /**
     * Store manager
     *
     * @var \PassKeeper\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Page factory
     *
     * @var \PassKeeper\Cms\Model\PageFactory
     */
    protected $_pageFactory;

    /**
     * Config primary
     *
     * @var \PassKeeper\Framework\App\State
     */
    protected $_appState;

    /**
     * Url
     *
     * @var \PassKeeper\Framework\UrlInterface
     */
    protected $_url;

    /**
     * Response
     *
     * @var \PassKeeper\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @param \PassKeeper\Framework\App\ActionFactory $actionFactory
     * @param \PassKeeper\Framework\Event\ManagerInterface $eventManager
     * @param \PassKeeper\Framework\UrlInterface $url
     * @param \PassKeeper\Cms\Model\PageFactory $pageFactory
     * @param \PassKeeper\Store\Model\StoreManagerInterface $storeManager
     * @param \PassKeeper\Framework\App\ResponseInterface $response
     */
    public function __construct(
        \PassKeeper\Framework\App\ActionFactory $actionFactory,
        \PassKeeper\Framework\Event\ManagerInterface $eventManager,
        \PassKeeper\Framework\UrlInterface $url,
        \PassKeeper\Cms\Model\PageFactory $pageFactory,
        \PassKeeper\Store\Model\StoreManagerInterface $storeManager,
        \PassKeeper\Framework\App\ResponseInterface $response
    ) {
        $this->actionFactory = $actionFactory;
        $this->_eventManager = $eventManager;
        $this->_url = $url;
        $this->_pageFactory = $pageFactory;
        $this->_storeManager = $storeManager;
        $this->_response = $response;
    }

    /**
     * Validate and Match Cms Page and modify request
     *
     * @param \PassKeeper\Framework\App\RequestInterface $request
     * @return \PassKeeper\Framework\App\ActionInterface|null
     */
    public function match(\PassKeeper\Framework\App\RequestInterface $request)
    {
        $identifier = trim($request->getPathInfo(), '/');

        $condition = new \PassKeeper\Framework\DataObject(['identifier' => $identifier, 'continue' => true]);
        $this->_eventManager->dispatch(
            'cms_controller_router_match_before',
            ['router' => $this, 'condition' => $condition]
        );
        $identifier = $condition->getIdentifier();

        if ($condition->getRedirectUrl()) {
            $this->_response->setRedirect($condition->getRedirectUrl());
            $request->setDispatched(true);
            return $this->actionFactory->create(\PassKeeper\Framework\App\Action\Redirect::class);
        }

        if (!$condition->getContinue()) {
            return null;
        }

        /** @var \PassKeeper\Cms\Model\Page $page */
        $page = $this->_pageFactory->create();
        $pageId = $page->checkIdentifier($identifier, $this->_storeManager->getStore()->getId());
        if (!$pageId) {
            return null;
        }

        $request->setModuleName('cms')->setControllerName('page')->setActionName('view')->setParam('page_id', $pageId);
        $request->setAlias(\PassKeeper\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);

        return $this->actionFactory->create(\PassKeeper\Framework\App\Action\Forward::class);
    }
}
