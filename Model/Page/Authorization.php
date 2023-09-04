<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace PassKeeper\Cms\Model\Page;

use PassKeeper\Cms\Api\Data\PageInterface;
use PassKeeper\Cms\Api\PageRepositoryInterface;
use PassKeeper\Framework\AuthorizationInterface;
use PassKeeper\Framework\Exception\AuthorizationException;
use PassKeeper\Framework\App\Config\ScopeConfigInterface;
use PassKeeper\Store\Model\ScopeInterface;
use \PassKeeper\Store\Model\StoreManagerInterface;

/**
 * Authorization for saving a page.
 */
class Authorization
{
    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param PageRepositoryInterface $pageRepository
     * @param AuthorizationInterface $authorization
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        PageRepositoryInterface $pageRepository,
        AuthorizationInterface $authorization,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->pageRepository = $pageRepository;
        $this->authorization = $authorization;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * Check whether the design fields have been changed.
     *
     * @param PageInterface $page
     * @param PageInterface|null $oldPage
     * @return bool
     * @throws \PassKeeper\Framework\Exception\NoSuchEntityException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private function hasPageChanged(PageInterface $page, ?PageInterface $oldPage): bool
    {
        if (!$oldPage) {
            $oldPageLayout = $this->scopeConfig->getValue(
                'web/default_layouts/default_cms_layout',
                ScopeInterface::SCOPE_STORE,
                $this->storeManager->getStore()
            );
            if ($page->getPageLayout() && $page->getPageLayout() !== $oldPageLayout) {
                //If page layout is set and it's not a default value - design attributes are changed.
                return true;
            }
            //Otherwise page layout is empty and is OK to save.
            $oldPageLayout = $page->getPageLayout();
        } else {
            //Compare page layout to saved value.
            $oldPageLayout = $oldPage->getPageLayout();
        }
        //Compare new values to saved values or require them to be empty
        $oldUpdateXml = $oldPage ? $oldPage->getLayoutUpdateXml() : null;
        $oldCustomTheme = $oldPage ? $oldPage->getCustomTheme() : null;
        $oldLayoutUpdate = $oldPage ? $oldPage->getCustomLayoutUpdateXml() : null;
        $oldThemeFrom = $oldPage ? $oldPage->getCustomThemeFrom() : null;
        $oldThemeTo = $oldPage ? $oldPage->getCustomThemeTo() : null;

        if ($page->getLayoutUpdateXml() != $oldUpdateXml
            || $page->getPageLayout() != $oldPageLayout
            || $page->getCustomTheme() != $oldCustomTheme
            || $page->getCustomLayoutUpdateXml() != $oldLayoutUpdate
            || $page->getCustomThemeFrom() != $oldThemeFrom
            || $page->getCustomThemeTo() != $oldThemeTo
        ) {
            return true;
        }

        return false;
    }

    /**
     * Authorize user before updating a page.
     *
     * @param PageInterface $page
     * @return void
     * @throws AuthorizationException
     * @throws \PassKeeper\Framework\Exception\LocalizedException When it is impossible to perform authorization.
     */
    public function authorizeFor(PageInterface $page): void
    {
        //Validate design changes.
        if (!$this->authorization->isAllowed('PassKeeper_Cms::save_design')) {
            $oldPage = null;
            if ($page->getId()) {
                $oldPage = $this->pageRepository->getById($page->getId());
            }
            if ($this->hasPageChanged($page, $oldPage)) {
                throw new AuthorizationException(
                    __('You are not allowed to change CMS pages design settings')
                );
            }
        }
    }
}
