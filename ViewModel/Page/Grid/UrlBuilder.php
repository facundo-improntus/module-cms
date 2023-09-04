<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace PassKeeper\Cms\ViewModel\Page\Grid;

use PassKeeper\Framework\Url\EncoderInterface;
use PassKeeper\Framework\App\ActionInterface;
use PassKeeper\Store\Model\StoreManagerInterface;

/**
 * Url builder class used to compose dynamic urls.
 */
class UrlBuilder
{
    /**
     * @var \PassKeeper\Framework\UrlInterface
     */
    private $frontendUrlBuilder;

    /**
     * @var EncoderInterface
     */
    private $urlEncoder;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param \PassKeeper\Framework\UrlInterface $frontendUrlBuilder
     * @param EncoderInterface $urlEncoder
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        \PassKeeper\Framework\UrlInterface $frontendUrlBuilder,
        EncoderInterface $urlEncoder,
        StoreManagerInterface $storeManager
    ) {
        $this->frontendUrlBuilder = $frontendUrlBuilder;
        $this->urlEncoder = $urlEncoder;
        $this->storeManager = $storeManager;
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
            $targetUrl = $this->frontendUrlBuilder->getUrl(
                $routePath,
                [
                    '_current' => false,
                    '_nosid' => true,
                    '_query' => [
                        StoreManagerInterface::PARAM_NAME => $store
                    ]
                ]
            );
            $href = $this->frontendUrlBuilder->getUrl(
                'stores/store/switch',
                [
                    '_current' => false,
                    '_nosid' => true,
                    '_query' => $this->prepareRequestQuery($store, $targetUrl)
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

    /**
     * Prepare request query
     *
     * @param string $store
     * @param string $href
     * @return array
     */
    private function prepareRequestQuery(string $store, string $href) : array
    {
        $storeView = $this->storeManager->getDefaultStoreView();
        $query = [
            StoreManagerInterface::PARAM_NAME => $store,
            ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlEncoder->encode($href)
        ];
        if (null !== $storeView && $storeView->getCode() !== $store) {
            $query['___from_store'] = $storeView->getCode();
        }

        return $query;
    }
}
