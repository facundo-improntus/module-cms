<?php
/**
 *
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Block;

use PassKeeper\Framework\App\Action\HttpPostActionInterface;

class Delete extends \PassKeeper\Cms\Controller\Adminhtml\Block implements HttpPostActionInterface
{
    /**
     * Delete action
     *
     * @return \PassKeeper\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \PassKeeper\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('block_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\PassKeeper\Cms\Model\Block::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the block.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['block_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a block to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
