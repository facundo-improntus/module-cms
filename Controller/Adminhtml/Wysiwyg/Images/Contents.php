<?php
/**
 *
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Wysiwyg\Images;

class Contents extends \PassKeeper\Cms\Controller\Adminhtml\Wysiwyg\Images
{
    /**
     * @var \PassKeeper\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @var \PassKeeper\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @param \PassKeeper\Backend\App\Action\Context $context
     * @param \PassKeeper\Framework\Registry $coreRegistry
     * @param \PassKeeper\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param \PassKeeper\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \PassKeeper\Backend\App\Action\Context $context,
        \PassKeeper\Framework\Registry $coreRegistry,
        \PassKeeper\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \PassKeeper\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Save current path in session
     *
     * @return $this
     */
    protected function _saveSessionCurrentPath()
    {
        $this->getStorage()->getSession()->setCurrentPath(
            $this->_objectManager->get(\PassKeeper\Cms\Helper\Wysiwyg\Images::class)->getCurrentPath()
        );
        return $this;
    }

    /**
     * Contents action
     *
     * @return \PassKeeper\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $this->_initAction()->_saveSessionCurrentPath();
            /** @var \PassKeeper\Framework\View\Result\Layout $resultLayout */
            $resultLayout = $this->resultLayoutFactory->create();
            return $resultLayout;
        } catch (\Exception $e) {
            $result = ['error' => true, 'message' => $e->getMessage()];
            /** @var \PassKeeper\Framework\Controller\Result\Json $resultJson */
            $resultJson = $this->resultJsonFactory->create();
            $resultJson->setData($result);
            return $resultJson;
        }
    }
}
