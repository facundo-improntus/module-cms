<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Block\Adminhtml;

/**
 * Adminhtml cms blocks content block
 */
class Block extends \PassKeeper\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'PassKeeper_Cms';
        $this->_controller = 'adminhtml_block';
        $this->_headerText = __('Static Blocks');
        $this->_addButtonLabel = __('Add New Block');
        parent::_construct();
    }
}
