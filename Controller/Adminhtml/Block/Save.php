<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Controller\Adminhtml\Block;

use PassKeeper\Framework\App\Action\HttpPostActionInterface;
use PassKeeper\Backend\App\Action\Context;
use PassKeeper\Cms\Api\BlockRepositoryInterface;
use PassKeeper\Cms\Model\Block;
use PassKeeper\Cms\Model\BlockFactory;
use PassKeeper\Framework\App\Request\DataPersistorInterface;
use PassKeeper\Framework\Exception\LocalizedException;
use PassKeeper\Framework\Registry;

/**
 * Save CMS block action.
 */
class Save extends \PassKeeper\Cms\Controller\Adminhtml\Block implements HttpPostActionInterface
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     * @param BlockFactory|null $blockFactory
     * @param BlockRepositoryInterface|null $blockRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        DataPersistorInterface $dataPersistor,
        BlockFactory $blockFactory = null,
        BlockRepositoryInterface $blockRepository = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->blockFactory = $blockFactory
            ?: \PassKeeper\Framework\App\ObjectManager::getInstance()->get(BlockFactory::class);
        $this->blockRepository = $blockRepository
            ?: \PassKeeper\Framework\App\ObjectManager::getInstance()->get(BlockRepositoryInterface::class);
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \PassKeeper\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \PassKeeper\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Block::STATUS_ENABLED;
            }
            if (empty($data['block_id'])) {
                $data['block_id'] = null;
            }

            /** @var \PassKeeper\Cms\Model\Block $model */
            $model = $this->blockFactory->create();

            $id = $this->getRequest()->getParam('block_id');
            if ($id) {
                try {
                    $model = $this->blockRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This block no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $this->blockRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the block.'));
                $this->dataPersistor->clear('cms_block');
                return $this->processBlockReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the block.'));
            }

            $this->dataPersistor->set('cms_block', $data);
            return $resultRedirect->setPath('*/*/edit', ['block_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process and set the block return
     *
     * @param \PassKeeper\Cms\Model\Block $model
     * @param array $data
     * @param \PassKeeper\Framework\Controller\ResultInterface $resultRedirect
     * @return \PassKeeper\Framework\Controller\ResultInterface
     */
    private function processBlockReturn($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';

        if ($redirect ==='continue') {
            $resultRedirect->setPath('*/*/edit', ['block_id' => $model->getId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        } elseif ($redirect === 'duplicate') {
            $duplicateModel = $this->blockFactory->create(['data' => $data]);
            $duplicateModel->setId(null);
            $duplicateModel->setIdentifier($data['identifier'] . '-' . uniqid());
            $duplicateModel->setIsActive(Block::STATUS_DISABLED);
            $this->blockRepository->save($duplicateModel);
            $id = $duplicateModel->getId();
            $this->messageManager->addSuccessMessage(__('You duplicated the block.'));
            $this->dataPersistor->set('cms_block', $data);
            $resultRedirect->setPath('*/*/edit', ['block_id' => $id]);
        }
        return $resultRedirect;
    }
}
