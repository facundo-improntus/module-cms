<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Api;

/**
 * Utility Cms Pages
 *
 * @api
 * @since 102.0.4
 */
interface GetUtilityPageIdentifiersInterface
{
    /**
     * Get List Page Identifiers
     * @return array
     * @since 102.0.4
     */
    public function execute();
}
