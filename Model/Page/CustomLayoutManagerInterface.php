<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace PassKeeper\Cms\Model\Page;

use PassKeeper\Cms\Api\Data\PageInterface;
use PassKeeper\Cms\Model\Page\CustomLayout\Data\CustomLayoutSelectedInterface;
use PassKeeper\Framework\View\Result\Page as View;

/**
 * Manage custom layout files for CMS pages.
 *
 * @api
 */
interface CustomLayoutManagerInterface
{
    /**
     * List of available custom files for the given page.
     *
     * @param PageInterface $page
     * @return string[]
     */
    public function fetchAvailableFiles(PageInterface $page): array;

    /**
     * Apply the page's layout settings.
     *
     * @param View $layout
     * @param CustomLayoutSelectedInterface $layoutSelected
     * @return void
     */
    public function applyUpdate(View $layout, CustomLayoutSelectedInterface $layoutSelected): void;
}
