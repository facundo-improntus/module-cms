<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Wysiwyg\Images;

use PassKeeper\Framework\App\Action\HttpPostActionInterface;
use PassKeeper\Framework\App\Filesystem\DirectoryList;

/**
 * Delete image files.
 */
class DeleteFiles extends \PassKeeper\Cms\Controller\Adminhtml\Wysiwyg\Images implements HttpPostActionInterface
{
    /**
     * @var \PassKeeper\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \PassKeeper\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \PassKeeper\Framework\App\Filesystem\DirectoryResolver
     */
    private $directoryResolver;

    /**
     * Constructor
     *
     * @param \PassKeeper\Backend\App\Action\Context $context
     * @param \PassKeeper\Framework\Registry $coreRegistry
     * @param \PassKeeper\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \PassKeeper\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \PassKeeper\Framework\App\Filesystem\DirectoryResolver|null $directoryResolver
     */
    public function __construct(
        \PassKeeper\Backend\App\Action\Context $context,
        \PassKeeper\Framework\Registry $coreRegistry,
        \PassKeeper\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \PassKeeper\Framework\Controller\Result\RawFactory $resultRawFactory,
        \PassKeeper\Framework\App\Filesystem\DirectoryResolver $directoryResolver = null
    ) {
        parent::__construct($context, $coreRegistry);

        $this->resultRawFactory = $resultRawFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->directoryResolver = $directoryResolver
            ?: $this->_objectManager->get(\PassKeeper\Framework\App\Filesystem\DirectoryResolver::class);
    }

    /**
     * Delete file from media storage.
     *
     * @return \PassKeeper\Framework\Controller\ResultInterface
     * @throws \PassKeeper\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $result = [];
        if (!$this->getRequest()->isPost()) {
            $result = ['error' => true, 'message' => __('Wrong request.')];
            /** @var \PassKeeper\Framework\Controller\Result\Json $resultJson */
            return $resultJson->setData($result);
        }

        try {
            $files = $this->getRequest()->getParam('files');

            /** @var $helper \PassKeeper\Cms\Helper\Wysiwyg\Images */
            $helper = $this->_objectManager->get(\PassKeeper\Cms\Helper\Wysiwyg\Images::class);
            $path = $this->getStorage()->getSession()->getCurrentPath();
            if (!$this->directoryResolver->validatePath($path, DirectoryList::MEDIA)) {
                throw new \PassKeeper\Framework\Exception\LocalizedException(
                    __('Directory %1 is not under storage root path.', $path)
                );
            }
            foreach ($files as $file) {
                $file = $helper->idDecode($file);
                /** @var \PassKeeper\Framework\Filesystem $filesystem */
                $filesystem = $this->_objectManager->get(\PassKeeper\Framework\Filesystem::class);
                $dir = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
                $filePath = $path . '/' . $file;
                if ($dir->isFile($dir->getRelativePath($filePath)) && !preg_match('#.htaccess#', $file)) {
                    $this->getStorage()->deleteFile($filePath);
                }
            }

            return $this->resultRawFactory->create();
            // phpcs:ignore PassKeeper2.Exceptions.ThrowCatch
        } catch (\Exception $e) {
            $result = ['error' => true, 'message' => $e->getMessage()];
        }
        return $resultJson->setData($result);
    }
}
