<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Model;

use PassKeeper\Cms\Api\GetUtilityPageIdentifiersInterface;
use PassKeeper\Framework\App\Config\ScopeConfigInterface;
use PassKeeper\Store\Model\ScopeInterface;

/**
 * Utility Cms Pages.
 */
class GetUtilityPageIdentifiers implements GetUtilityPageIdentifiersInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * UtilityCmsPage constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get List Page Identifiers.
     *
     * @return array
     */
    public function execute()
    {
        $homePageIdentifier = $this->scopeConfig->getValue(
            'web/default/cms_home_page',
            ScopeInterface::SCOPE_STORE
        );
        $noRouteIdentifier  = $this->scopeConfig->getValue(
            'web/default/cms_no_route',
            ScopeInterface::SCOPE_STORE
        );

        $noCookieIdentifier = $this->scopeConfig->getValue(
            'web/default/cms_no_cookies',
            ScopeInterface::SCOPE_STORE
        );

        return [$homePageIdentifier, $noRouteIdentifier, $noCookieIdentifier];
    }
}
