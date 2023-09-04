<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Block\Adminhtml\Wysiwyg\Images\Content;

/**
 * Directory contents block for Wysiwyg Images
 *
 * @api
 * @since 100.0.2
 */
class Files extends \PassKeeper\Backend\Block\Template
{
    /**
     * Files collection object
     *
     * @var \PassKeeper\Framework\Data\Collection\Filesystem
     */
    protected $_filesCollection;

    /**
     * @var \PassKeeper\Cms\Model\Wysiwyg\Images\Storage
     */
    protected $_imageStorage;

    /**
     * @var \PassKeeper\Cms\Helper\Wysiwyg\Images
     */
    protected $_imageHelper;

    /**
     * @param \PassKeeper\Backend\Block\Template\Context $context
     * @param \PassKeeper\Cms\Model\Wysiwyg\Images\Storage $imageStorage
     * @param \PassKeeper\Cms\Helper\Wysiwyg\Images $imageHelper
     * @param array $data
     */
    public function __construct(
        \PassKeeper\Backend\Block\Template\Context $context,
        \PassKeeper\Cms\Model\Wysiwyg\Images\Storage $imageStorage,
        \PassKeeper\Cms\Helper\Wysiwyg\Images $imageHelper,
        array $data = []
    ) {
        $this->_imageHelper = $imageHelper;
        $this->_imageStorage = $imageStorage;
        parent::__construct($context, $data);
    }

    /**
     * Prepared Files collection for current directory
     *
     * @return \PassKeeper\Framework\Data\Collection\Filesystem
     */
    public function getFiles()
    {
        if (!$this->_filesCollection) {
            $this->_filesCollection = $this->_imageStorage->getFilesCollection(
                $this->_imageHelper->getCurrentPath(),
                $this->_getMediaType()
            );
        }

        return $this->_filesCollection;
    }

    /**
     * Files collection count getter
     *
     * @return int
     */
    public function getFilesCount()
    {
        return $this->getFiles()->count();
    }

    /**
     * File identifier getter
     *
     * @param  \PassKeeper\Framework\DataObject $file
     * @return string
     */
    public function getFileId(\PassKeeper\Framework\DataObject $file)
    {
        return $file->getId();
    }

    /**
     * File thumb URL getter
     *
     * @param  \PassKeeper\Framework\DataObject $file
     * @return string
     */
    public function getFileThumbUrl(\PassKeeper\Framework\DataObject $file)
    {
        return $file->getThumbUrl();
    }

    /**
     * File name URL getter
     *
     * @param  \PassKeeper\Framework\DataObject $file
     * @return string
     */
    public function getFileName(\PassKeeper\Framework\DataObject $file)
    {
        return $file->getName();
    }

    /**
     * Image file width getter
     *
     * @param  \PassKeeper\Framework\DataObject $file
     * @return string
     */
    public function getFileWidth(\PassKeeper\Framework\DataObject $file)
    {
        return $file->getWidth();
    }

    /**
     * Image file height getter
     *
     * @param  \PassKeeper\Framework\DataObject $file
     * @return string
     */
    public function getFileHeight(\PassKeeper\Framework\DataObject $file)
    {
        return $file->getHeight();
    }

    /**
     * File short name getter
     *
     * @param  \PassKeeper\Framework\DataObject $file
     * @return string
     */
    public function getFileShortName(\PassKeeper\Framework\DataObject $file)
    {
        return $file->getShortName();
    }

    /**
     * Get image width
     *
     * @return int
     */
    public function getImagesWidth()
    {
        return $this->_imageStorage->getResizeWidth();
    }

    /**
     * Get image height
     *
     * @return int
     */
    public function getImagesHeight()
    {
        return $this->_imageStorage->getResizeHeight();
    }

    /**
     * Return current media type based on request or data
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
