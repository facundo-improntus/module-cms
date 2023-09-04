<?php
/**
 *
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace PassKeeper\Cms\Controller\Adminhtml\Wysiwyg\Images;

use PassKeeper\Framework\App\Action\HttpPostActionInterface;

/**
 * Delete image folder.
 */
class DeleteFolder extends \PassKeeper\Cms\Controller\Adminhtml\Wysiwyg\Images implements HttpPostActionInterface
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
     * Delete folder action.
     *
     * @return \PassKeeper\Framework\Controller\ResultInterface
     * @throws \PassKeeper\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $result = [];
        try {
            $path = $this->getStorage()->getCmsWysiwygImages()->getCurrentPath();
            $this->getStorage()->deleteDirectory($path);
        } catch (\Exception $e) {
            $result = ['error' => true, 'message' => $e->getMessage()];
        }
        /** @var \PassKeeper\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        return $resultJson->setData($result);
    }
}
