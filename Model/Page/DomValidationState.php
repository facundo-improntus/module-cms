<?php
/**
 * Application config file resolver
 *
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Model\Page;

/**
 * Class DomValidationState
 * @package PassKeeper\Cms\Model\Page
 */
class DomValidationState implements \PassKeeper\Framework\Config\ValidationStateInterface
{
    /**
     * Retrieve validation state
     * Used in cms page post processor to force validate layout update xml
     *
     * @return boolean
     */
    public function isValidationRequired()
    {
        return true;
    }
}
