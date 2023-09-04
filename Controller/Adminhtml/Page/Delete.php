<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Page;

use PassKeeper\Framework\App\Action\HttpPostActionInterface;

/**
 * Delete CMS page action.
 */
class Delete extends \PassKeeper\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PassKeeper_Cms::page_delete';

    /**
     * Delete action
     *
     * @return \PassKeeper\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('page_id');
        /** @var \PassKeeper\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if ($id) {
            $title = "";
            try {
                // init model and delete
                $model = $this->_objectManager->create(\PassKeeper\Cms\Model\Page::class);
                $model->load($id);
                
                $title = $model->getTitle();
                $model->delete();
                
                // display success message
                $this->messageManager->addSuccessMessage(__('The page has been deleted.'));
                
                // go to grid
                $this->_eventManager->dispatch('adminhtml_cmspage_on_delete', [
                    'title' => $title,
                    'status' => 'success'
                ]);
                
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_cmspage_on_delete',
                    ['title' => $title, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['page_id' => $id]);
            }
        }
        
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a page to delete.'));
        
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
