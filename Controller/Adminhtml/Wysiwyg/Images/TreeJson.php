<?php
/**
 *
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Wysiwyg\Images;

class TreeJson extends \PassKeeper\Cms\Controller\Adminhtml\Wysiwyg\Images
{
    /**
     * @var \PassKeeper\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \PassKeeper\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @param \PassKeeper\Backend\App\Action\Context $context
     * @param \PassKeeper\Framework\Registry $coreRegistry
     * @param \PassKeeper\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \PassKeeper\Framework\View\LayoutFactory $layoutFactory
     */
    public function __construct(
        \PassKeeper\Backend\App\Action\Context $context,
        \PassKeeper\Framework\Registry $coreRegistry,
        \PassKeeper\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \PassKeeper\Framework\View\LayoutFactory $layoutFactory
    ) {
        $this->layoutFactory = $layoutFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Tree json action
     *
     * @return \PassKeeper\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \PassKeeper\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        try {
            $this->_initAction();
            /** @var \PassKeeper\Framework\View\Layout $layout */
            $layout = $this->layoutFactory->create();
            $resultJson->setJsonData(
                $layout->createBlock(
                    \PassKeeper\Cms\Block\Adminhtml\Wysiwyg\Images\Tree::class
                )->getTreeJson()
            );
        } catch (\Exception $e) {
            $result = ['error' => true, 'message' => $e->getMessage()];
            $resultJson->setData($result);
        }
        return $resultJson;
    }
}
