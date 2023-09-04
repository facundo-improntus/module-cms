<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Wysiwyg;

/**
 * Images manage controller for Cms WYSIWYG editor
 *
 * @author      PassKeeper Core Team <core@passkeepercommerce.com>
 */
abstract class Images extends \PassKeeper\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PassKeeper_Cms::media_gallery';

    /**
     * Core registry
     *
     * @var \PassKeeper\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \PassKeeper\Backend\App\Action\Context $context
     * @param \PassKeeper\Framework\Registry $coreRegistry
     */
    public function __construct(\PassKeeper\Backend\App\Action\Context $context, \PassKeeper\Framework\Registry $coreRegistry)
    {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init storage
     *
     * @return $this
     */
    protected function _initAction()
    {
        $this->getStorage();
        return $this;
    }

    /**
     * Register storage model and return it
     *
     * @return \PassKeeper\Cms\Model\Wysiwyg\Images\Storage
     */
    public function getStorage()
    {
        if (!$this->_coreRegistry->registry('storage')) {
            $storage = $this->_objectManager->create(\PassKeeper\Cms\Model\Wysiwyg\Images\Storage::class);
            $this->_coreRegistry->register('storage', $storage);
        }
        return $this->_coreRegistry->registry('storage');
    }
}
