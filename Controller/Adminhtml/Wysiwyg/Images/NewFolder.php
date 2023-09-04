<?php
/**
 *
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Wysiwyg\Images;

use PassKeeper\Framework\App\Action\HttpPostActionInterface;
use PassKeeper\Framework\App\Filesystem\DirectoryList;

/**
 * Creates new folder.
 */
class NewFolder extends \PassKeeper\Cms\Controller\Adminhtml\Wysiwyg\Images implements HttpPostActionInterface
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
     * New folder action.
     *
     * @return \PassKeeper\Framework\Controller\ResultInterface
     * @throws \PassKeeper\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        try {
            $this->_initAction();
            $name = $this->getRequest()->getPost('name');
            $path = $this->getStorage()->getSession()->getCurrentPath();
            if (!$this->directoryResolver->validatePath($path, DirectoryList::MEDIA)) {
                throw new \PassKeeper\Framework\Exception\LocalizedException(
                    __('Directory %1 is not under storage root path.', $path)
                );
            }
            $result = $this->getStorage()->createDirectory($name, $path);
        } catch (\Exception $e) {
            $result = ['error' => true, 'message' => $e->getMessage()];
        }
        /** @var \PassKeeper\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        return $resultJson->setData($result);
    }
}
