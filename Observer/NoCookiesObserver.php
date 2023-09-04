<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Observer;

use PassKeeper\Framework\Event\ObserverInterface;

class NoCookiesObserver implements ObserverInterface
{
    /**
     * Cms page
     *
     * @var \PassKeeper\Cms\Helper\Page
     */
    protected $_cmsPage;

    /**
     * Core store config
     *
     * @var \PassKeeper\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @param \PassKeeper\Cms\Helper\Page $cmsPage
     * @param \PassKeeper\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \PassKeeper\Cms\Helper\Page $cmsPage,
        \PassKeeper\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_cmsPage = $cmsPage;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Modify no Cookies forward object
     *
     * @param \PassKeeper\Framework\Event\Observer $observer
     * @return self
     */
    public function execute(\PassKeeper\Framework\Event\Observer $observer)
    {
        $redirect = $observer->getEvent()->getRedirect();

        $pageId = $this->_scopeConfig->getValue(
            \PassKeeper\Cms\Helper\Page::XML_PATH_NO_COOKIES_PAGE,
            \PassKeeper\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $pageUrl = $this->_cmsPage->getPageUrl($pageId);

        if ($pageUrl) {
            $redirect->setRedirectUrl($pageUrl);
        } else {
            $redirect->setRedirect(true)->setPath('cookie/index/noCookies')->setArguments([]);
        }
        return $this;
    }
}
