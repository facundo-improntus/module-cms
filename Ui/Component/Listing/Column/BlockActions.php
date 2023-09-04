<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Ui\Component\Listing\Column;

use PassKeeper\Framework\UrlInterface;
use PassKeeper\Framework\View\Element\UiComponent\ContextInterface;
use PassKeeper\Framework\View\Element\UiComponentFactory;
use PassKeeper\Ui\Component\Listing\Columns\Column;
use PassKeeper\Framework\App\ObjectManager;
use PassKeeper\Framework\Escaper;

/**
 * Class to build edit and delete link for each item.
 */
class BlockActions extends Column
{
    /**
     * Url path
     */
    public const URL_PATH_EDIT = 'cms/block/edit';
    public const URL_PATH_DELETE = 'cms/block/delete';
    public const URL_PATH_DETAILS = 'cms/block/details';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['block_id'])) {
                    $title = $this->getEscaper()->escapeHtmlAttr($item['title']);
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'block_id' => $item['block_id']
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'block_id' => $item['block_id']
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete %1', $title),
                                'message' => __('Are you sure you want to delete a %1 record?', $title)
                            ],
                            'post' => true
                        ],
                    ];
                }
            }
        }

        return $dataSource;
    }

    /**
     * Get instance of escaper
     *
     * @return Escaper
     * @deprecated 101.0.7
     */
    private function getEscaper()
    {
        if (!$this->escaper) {
            // phpcs:ignore PassKeeper2.PHP.AutogeneratedClassNotInConstructor
            $this->escaper = ObjectManager::getInstance()->get(Escaper::class);
        }
        return $this->escaper;
    }
}