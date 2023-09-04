<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace PassKeeper\Cms\Model\Page\CustomLayout\Data;

/**
 * Custom layout update file to be used for the specific CMS page.
 *
 * @api
 */
interface CustomLayoutSelectedInterface
{
    /**
     * CMS page ID.
     *
     * @return int
     */
    public function getPageId(): int;

    /**
     * Custom layout file ID (layout update handle value).
     *
     * @return string
     */
    public function getLayoutFileId(): string;
}
