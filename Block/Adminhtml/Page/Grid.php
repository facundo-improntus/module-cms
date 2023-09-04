<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Block\Adminhtml\Page;

/**
 * Adminhtml cms pages grid
 */
class Grid extends \PassKeeper\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \PassKeeper\Cms\Model\ResourceModel\Page\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \PassKeeper\Cms\Model\Page
     */
    protected $_cmsPage;

    /**
     * @var \PassKeeper\Framework\View\Model\PageLayout\Config\BuilderInterface
     */
    protected $pageLayoutBuilder;

    /**
     * @param \PassKeeper\Backend\Block\Template\Context $context
     * @param \PassKeeper\Backend\Helper\Data $backendHelper
     * @param \PassKeeper\Cms\Model\Page $cmsPage
     * @param \PassKeeper\Cms\Model\ResourceModel\Page\CollectionFactory $collectionFactory
     * @param \PassKeeper\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder
     * @param array $data
     */
    public function __construct(
        \PassKeeper\Backend\Block\Template\Context $context,
        \PassKeeper\Backend\Helper\Data $backendHelper,
        \PassKeeper\Cms\Model\Page $cmsPage,
        \PassKeeper\Cms\Model\ResourceModel\Page\CollectionFactory $collectionFactory,
        \PassKeeper\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_cmsPage = $cmsPage;
        $this->pageLayoutBuilder = $pageLayoutBuilder;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('cmsPageGrid');
        $this->setDefaultSort('identifier');
        $this->setDefaultDir('ASC');
    }

    /**
     * Prepare collection
     *
     * @return \PassKeeper\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->create();
        /* @var $collection \PassKeeper\Cms\Model\ResourceModel\Page\Collection */
        $collection->setFirstStoreFlag(true);
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     *
     * @return \PassKeeper\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn('title', ['header' => __('Title'), 'index' => 'title']);

        $this->addColumn('identifier', ['header' => __('URL Key'), 'index' => 'identifier']);

        $this->addColumn(
            'page_layout',
            [
                'header' => __('Layout'),
                'index' => 'page_layout',
                'type' => 'options',
                'options' => $this->pageLayoutBuilder->getPageLayoutsConfig()->getOptions()
            ]
        );

        /**
         * Check is single store mode
         */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $this->addColumn(
                'store_id',
                [
                    'header' => __('Store View'),
                    'index' => 'store_id',
                    'type' => 'store',
                    'store_all' => true,
                    'store_view' => true,
                    'sortable' => false,
                    'filter_condition_callback' => [$this, '_filterStoreCondition']
                ]
            );
        }

        $this->addColumn(
            'is_active',
            [
                'header' => __('Status'),
                'index' => 'is_active',
                'type' => 'options',
                'options' => $this->_cmsPage->getAvailableStatuses()
            ]
        );

        $this->addColumn(
            'creation_time',
            [
                'header' => __('Created'),
                'index' => 'creation_time',
                'type' => 'datetime',
                'header_css_class' => 'col-date',
                'column_css_class' => 'col-date'
            ]
        );

        $this->addColumn(
            'update_time',
            [
                'header' => __('Modified'),
                'index' => 'update_time',
                'type' => 'datetime',
                'header_css_class' => 'col-date',
                'column_css_class' => 'col-date'
            ]
        );

        $this->addColumn(
            'page_actions',
            [
                'header' => __('Action'),
                'sortable' => false,
                'filter' => false,
                'renderer' => \PassKeeper\Cms\Block\Adminhtml\Page\Grid\Renderer\Action::class,
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * After load collection
     *
     * @return void
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * Filter store condition
     *
     * @param \PassKeeper\Framework\Data\Collection $collection
     * @param \PassKeeper\Framework\DataObject $column
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _filterStoreCondition($collection, \PassKeeper\Framework\DataObject $column)
    {
        if (!($value = $column->getFilter()->getValue())) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    /**
     * Row click url
     *
     * @param \PassKeeper\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['page_id' => $row->getId()]);
    }
}
