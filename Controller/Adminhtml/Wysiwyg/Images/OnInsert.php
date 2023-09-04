<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Wysiwyg\Images;

use PassKeeper\Backend\App\Action\Context;
use PassKeeper\Cms\Controller\Adminhtml\Wysiwyg\Images;
use PassKeeper\Cms\Model\Wysiwyg\Images\GetInsertImageContent;
use PassKeeper\Framework\App\Action\HttpPostActionInterface;
use PassKeeper\Framework\Controller\Result\RawFactory;
use PassKeeper\Framework\Controller\ResultInterface;
use PassKeeper\Framework\Registry;

class OnInsert extends Images implements HttpPostActionInterface
{
    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var GetInsertImageContent
     */
    private $getInsertImageContent;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param RawFactory $resultRawFactory
     * @param GetInsertImageContent $getInsertImageContent
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        RawFactory $resultRawFactory,
        ?GetInsertImageContent $getInsertImageContent = null
    ) {
        $this->resultRawFactory = $resultRawFactory;
        parent::__construct($context, $coreRegistry);
        $this->getInsertImageContent = $getInsertImageContent ?: $this->_objectManager
            ->get(GetInsertImageContent::class);
    }

    /**
     * Return a content (just a link or an html block) for inserting image to the content
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        return $this->resultRawFactory->create()->setContents(
            $this->getInsertImageContent->execute(
                $data['filename'],
                $data['force_static_path'],
                $data['as_is'],
                isset($data['store']) ? (int) $data['store'] : null
            )
        );
    }
}
