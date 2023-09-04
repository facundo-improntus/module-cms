<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Page\Widget;

use PassKeeper\Framework\App\Action\HttpGetActionInterface;
use PassKeeper\Framework\App\Action\HttpPostActionInterface;
use PassKeeper\Backend\App\Action;

/**
 * Chooser Source action.
 */
class Chooser extends Action implements HttpPostActionInterface, HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'PassKeeper_Widget::widget_instance';

    /**
     * @var \PassKeeper\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var \PassKeeper\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @param Action\Context $context
     * @param \PassKeeper\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \PassKeeper\Framework\View\LayoutFactory $layoutFactory
     */
    public function __construct(
        Action\Context $context,
        \PassKeeper\Framework\Controller\Result\RawFactory $resultRawFactory,
        \PassKeeper\Framework\View\LayoutFactory $layoutFactory
    ) {
        $this->layoutFactory = $layoutFactory;
        $this->resultRawFactory = $resultRawFactory;
        parent::__construct($context);
    }

    /**
     * Chooser Source action
     *
     * @return \PassKeeper\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        /** @var \PassKeeper\Framework\View\Layout $layout */
        $layout = $this->layoutFactory->create();
        $pagesGrid = $layout->createBlock(
            \PassKeeper\Cms\Block\Adminhtml\Page\Widget\Chooser::class,
            '',
            ['data' => ['id' => $uniqId]]
        );
        $html = $pagesGrid->toHtml();
        /** @var \PassKeeper\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();

        return $resultRaw->setContents($html);
    }
}
