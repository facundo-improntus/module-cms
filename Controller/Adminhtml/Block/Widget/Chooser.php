<?php
/**
 *
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Block\Widget;

use PassKeeper\Backend\App\Action\Context;
use PassKeeper\Framework\View\LayoutFactory;
use PassKeeper\Framework\Controller\Result\RawFactory;

class Chooser extends \PassKeeper\Backend\App\Action
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
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * @param Context $context
     * @param LayoutFactory $layoutFactory
     * @param RawFactory $resultRawFactory
     */
    public function __construct(Context $context, LayoutFactory $layoutFactory, RawFactory $resultRawFactory)
    {
        $this->layoutFactory = $layoutFactory;
        $this->resultRawFactory = $resultRawFactory;
        parent::__construct($context);
    }

    /**
     * Chooser Source action
     *
     * @return \PassKeeper\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \PassKeeper\Framework\View\Layout $layout */
        $layout = $this->layoutFactory->create();

        $uniqId = $this->getRequest()->getParam('uniq_id');
        $pagesGrid = $layout->createBlock(
            \PassKeeper\Cms\Block\Adminhtml\Block\Widget\Chooser::class,
            '',
            ['data' => ['id' => $uniqId]]
        );

        /** @var \PassKeeper\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        $resultRaw->setContents($pagesGrid->toHtml());
        return $resultRaw;
    }
}
