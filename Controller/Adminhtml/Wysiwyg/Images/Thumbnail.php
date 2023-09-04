<?php
/**
 *
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Wysiwyg\Images;

use PassKeeper\Backend\App\Action;

class Thumbnail extends \PassKeeper\Cms\Controller\Adminhtml\Wysiwyg\Images
{
    /**
     * @var \PassKeeper\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @param Action\Context $context
     * @param \PassKeeper\Framework\Registry $coreRegistry
     * @param \PassKeeper\Framework\Controller\Result\RawFactory $resultRawFactory
     */
    public function __construct(
        Action\Context $context,
        \PassKeeper\Framework\Registry $coreRegistry,
        \PassKeeper\Framework\Controller\Result\RawFactory $resultRawFactory
    ) {
        $this->resultRawFactory = $resultRawFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Generate image thumbnail on the fly
     *
     * @return \PassKeeper\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $file = $this->getRequest()->getParam('file');
        $file = $this->_objectManager->get(\PassKeeper\Cms\Helper\Wysiwyg\Images::class)->idDecode($file);
        $thumb = $this->getStorage()->resizeOnTheFly($file);
        /** @var \PassKeeper\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        if ($thumb !== false) {
            /** @var \PassKeeper\Framework\Image\Adapter\AdapterInterface $image */
            $image = $this->_objectManager->get(\PassKeeper\Framework\Image\AdapterFactory::class)->create();
            $image->open($thumb);
            $resultRaw->setHeader('Content-Type', $image->getMimeType());
            $resultRaw->setContents($image->getImage());
            return $resultRaw;
        } else {
            // todo: generate some placeholder
        }
    }
}
