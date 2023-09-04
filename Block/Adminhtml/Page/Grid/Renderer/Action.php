<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Block\Adminhtml\Page\Grid\Renderer;

class Action extends \PassKeeper\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @var Action\UrlBuilder
     */
    protected $actionUrlBuilder;

    /**
     * @param \PassKeeper\Backend\Block\Context $context
     * @param Action\UrlBuilder $actionUrlBuilder
     * @param array $data
     */
    public function __construct(
        \PassKeeper\Backend\Block\Context $context,
        Action\UrlBuilder $actionUrlBuilder,
        array $data = []
    ) {
        $this->actionUrlBuilder = $actionUrlBuilder;
        parent::__construct($context, $data);
    }

    /**
     * Render action
     *
     * @param \PassKeeper\Framework\DataObject $row
     * @return string
     */
    public function render(\PassKeeper\Framework\DataObject $row)
    {
        $href = $this->actionUrlBuilder->getUrl(
            $row->getIdentifier(),
            $row->getData('_first_store_id'),
            $row->getStoreCode()
        );
        return '<a href="' . $href . '" target="_blank">' . __('Preview') . '</a>';
    }
}
