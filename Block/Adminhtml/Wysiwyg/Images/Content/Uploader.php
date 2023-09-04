<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Block\Adminhtml\Wysiwyg\Images\Content;

/**
 * Uploader block for Wysiwyg Images
 *
 * @api
 * @since 100.0.2
 */
class Uploader extends \PassKeeper\Backend\Block\Media\Uploader
{
    /**
     * @var \PassKeeper\Cms\Model\Wysiwyg\Images\Storage
     */
    protected $_imagesStorage;

    /**
     * @param \PassKeeper\Backend\Block\Template\Context $context
     * @param \PassKeeper\Framework\File\Size $fileSize
     * @param \PassKeeper\Cms\Model\Wysiwyg\Images\Storage $imagesStorage
     * @param array $data
     */
    public function __construct(
        \PassKeeper\Backend\Block\Template\Context $context,
        \PassKeeper\Framework\File\Size $fileSize,
        \PassKeeper\Cms\Model\Wysiwyg\Images\Storage $imagesStorage,
        array $data = []
    ) {
        $this->_imagesStorage = $imagesStorage;
        parent::__construct($context, $fileSize, $data);
    }

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $type = $this->_getMediaType();
        $allowed = $this->_imagesStorage->getAllowedExtensions($type);
        $labels = [];
        $files = [];
        foreach ($allowed as $ext) {
            $labels[] = '.' . $ext;
            $files[] = '*.' . $ext;
        }
        $this->getConfig()->setUrl(
            $this->_urlBuilder->getUrl('cms/*/upload', ['type' => $type])
        )->setFileField(
            'image'
        )->setFilters(
            ['images' => ['label' => __('Images (%1)', implode(', ', $labels)), 'files' => $files]]
        );
    }

    /**
     * Return current media type based on request or data
     *
     * @return string
     */
    protected function _getMediaType()
    {
        if ($this->hasData('media_type')) {
            return $this->_getData('media_type');
        }
        return $this->getRequest()->getParam('type');
    }
}
