<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace PassKeeper\Cms\Model\Wysiwyg\Gallery;

use PassKeeper\Ui\Component\Form\Element\DataType\Media\OpenDialogUrl;

/**
 * @inheritdoc
 */
class DefaultConfigProvider implements \PassKeeper\Framework\Data\Wysiwyg\ConfigProviderInterface
{
    /**
     * @var \PassKeeper\Backend\Model\UrlInterface
     */
    private $backendUrl;

    /**
     * @var \PassKeeper\Cms\Helper\Wysiwyg\Images
     */
    private $imagesHelper;

    /**
     * @var array
     */
    private $windowSize;

    /**
     * @var string|null
     */
    private $currentTreePath;

    /**
     * @var OpednDialogUrl
     */
    private $openDialogUrl;

    /**
     * @param \PassKeeper\Backend\Model\UrlInterface $backendUrl
     * @param \PassKeeper\Cms\Helper\Wysiwyg\Images $imagesHelper
     * @param OpenDialogUrl $openDialogUrl
     * @param array $windowSize
     * @param string|null $currentTreePath
     */
    public function __construct(
        \PassKeeper\Backend\Model\UrlInterface $backendUrl,
        \PassKeeper\Cms\Helper\Wysiwyg\Images $imagesHelper,
        OpenDialogUrl $openDialogUrl,
        array $windowSize = [],
        $currentTreePath = null
    ) {
        $this->backendUrl = $backendUrl;
        $this->imagesHelper = $imagesHelper;
        $this->openDialogUrl = $openDialogUrl;
        $this->windowSize = $windowSize;
        $this->currentTreePath = $currentTreePath;
    }

    /**
     * @inheritdoc
     */
    public function getConfig(\PassKeeper\Framework\DataObject $config) : \PassKeeper\Framework\DataObject
    {
        $pluginData = (array) $config->getData('plugins');
        $imageData = [
            [
                'name' => 'image',
            ]
        ];

        $fileBrowserUrlParams = [];

        if (is_string($this->currentTreePath)) {
            $fileBrowserUrlParams = [
                'current_tree_path' => $this->imagesHelper->idEncode($this->currentTreePath),
            ];
        }

        return $config->addData(
            [
                'add_images' => true,
                'files_browser_window_url' => $this->backendUrl->getUrl(
                    $this->openDialogUrl->get(),
                    $fileBrowserUrlParams
                ),
                'files_browser_window_width' => $this->windowSize['width'],
                'files_browser_window_height' => $this->windowSize['height'],
                'plugins' => array_merge($pluginData, $imageData)
            ]
        );
    }
}
