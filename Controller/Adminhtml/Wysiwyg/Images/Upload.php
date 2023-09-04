<?php
/**
 *
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace PassKeeper\Cms\Controller\Adminhtml\Wysiwyg\Images;

use PassKeeper\Framework\App\Action\HttpPostActionInterface;
use PassKeeper\Framework\App\Filesystem\DirectoryList;

/**
 * Upload image.
 */
class Upload extends \PassKeeper\Cms\Controller\Adminhtml\Wysiwyg\Images implements HttpPostActionInterface
{
    /**
     * @var \PassKeeper\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \PassKeeper\Framework\App\Filesystem\DirectoryResolver
     */
    private $directoryResolver;

    /**
     * @param \PassKeeper\Backend\App\Action\Context $context
     * @param \PassKeeper\Framework\Registry $coreRegistry
     * @param \PassKeeper\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \PassKeeper\Framework\App\Filesystem\DirectoryResolver|null $directoryResolver
     */
    public function __construct(
        \PassKeeper\Backend\App\Action\Context $context,
        \PassKeeper\Framework\Registry $coreRegistry,
        \PassKeeper\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \PassKeeper\Framework\App\Filesystem\DirectoryResolver $directoryResolver = null
    ) {
        parent::__construct($context, $coreRegistry);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->directoryResolver = $directoryResolver
            ?: $this->_objectManager->get(\PassKeeper\Framework\App\Filesystem\DirectoryResolver::class);
    }

    /**
     * Files upload processing.
     *
     * @return \PassKeeper\Framework\Controller\ResultInterface
     * @throws \PassKeeper\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        try {
            $this->_initAction();
            $path = $this->getStorage()->getSession()->getCurrentPath();
            if (!$this->directoryResolver->validatePath($path, DirectoryList::MEDIA)) {
                throw new \PassKeeper\Framework\Exception\LocalizedException(
                    __('Directory %1 is not under storage root path.', $path)
                );
            }
            $uploaded = $this->getStorage()->uploadFile($path, $this->getRequest()->getParam('type'));
            $response = [
                'name' => $uploaded['name'],
                'type' => $uploaded['type'],
                'error' => $uploaded['error'],
                'size' => $uploaded['size'],
                'file' => $uploaded['file']
            ];
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        /** @var \PassKeeper\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        return $resultJson->setData($response);
    }
}
