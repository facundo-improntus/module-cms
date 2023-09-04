<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Ui\Component;

use PassKeeper\Framework\Api\Filter;
use PassKeeper\Framework\Api\FilterBuilder;
use PassKeeper\Framework\Api\Search\SearchCriteriaBuilder;
use PassKeeper\Framework\App\ObjectManager;
use PassKeeper\Framework\App\RequestInterface;
use PassKeeper\Framework\AuthorizationInterface;
use PassKeeper\Framework\View\Element\UiComponent\DataProvider\Reporting;

/**
 * DataProvider for cms ui.
 */
class DataProvider extends \PassKeeper\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @var AddFilterInterface[]
     */
    private $additionalFilterPool;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Reporting $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param array $meta
     * @param array $data
     * @param array $additionalFilterPool
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Reporting $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        array $meta = [],
        array $data = [],
        array $additionalFilterPool = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );

        $this->meta = array_replace_recursive($meta, $this->prepareMetadata());
        $this->additionalFilterPool = $additionalFilterPool;
    }

    /**
     * Get authorization info.
     *
     * @deprecated 101.0.7
     * @return AuthorizationInterface|mixed
     */
    private function getAuthorizationInstance()
    {
        if ($this->authorization === null) {
            $this->authorization = ObjectManager::getInstance()->get(AuthorizationInterface::class);
        }
        return $this->authorization;
    }

    /**
     * Prepares Meta
     *
     * @return array
     */
    public function prepareMetadata()
    {
        $metadata = [];

        if (!$this->getAuthorizationInstance()->isAllowed('PassKeeper_Cms::save')) {
            $metadata = [
                'cms_page_columns' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'editorConfig' => [
                                    'enabled' => false
                                ],
                                'componentType' => \PassKeeper\Ui\Component\Container::NAME
                            ]
                        ]
                    ]
                ]
            ];
        }

        return $metadata;
    }

    /**
     * @inheritdoc
     */
    public function addFilter(Filter $filter)
    {
        if (!empty($this->additionalFilterPool[$filter->getField()])) {
            $this->additionalFilterPool[$filter->getField()]->addFilter($this->searchCriteriaBuilder, $filter);
        } else {
            parent::addFilter($filter);
        }
    }
}
