<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Block\Adminhtml\Page\Grid\Renderer\Action;

/**
 * Url builder class used to compose dynamic urls.
 */
class UrlBuilder
{
    /**
     * @var \PassKeeper\Framework\UrlInterface
     */
    protected $frontendUrlBuilder;

    /**
     * @param \PassKeeper\Framework\UrlInterface $frontendUrlBuilder
     */
    public function __construct(\PassKeeper\Framework\UrlInterface $frontendUrlBuilder)
    {
        $this->frontendUrlBuilder = $frontendUrlBuilder;
    }

    /**
     * Get action url
     *
     * @param string $routePath
     * @param string $scope
     * @param string $store
     * @return string
     */
    public function getUrl($routePath, $scope, $store)
    {
        if ($scope) {
            $this->frontendUrlBuilder->setScope($scope);
            $href = $this->frontendUrlBuilder->getUrl(
                $routePath,
                [
                    '_current' => false,
                    '_nosid' => true,
                    '_query' => [\PassKeeper\Store\Model\StoreManagerInterface::PARAM_NAME => $store]
                ]
            );
        } else {
            $href = $this->frontendUrlBuilder->getUrl(
                $routePath,
                [
                    '_current' => false,
                    '_nosid' => true
                ]
            );
        }

        return $href;
    }
}
