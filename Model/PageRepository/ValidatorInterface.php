<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace PassKeeper\Cms\Model\PageRepository;

use PassKeeper\Cms\Api\Data\PageInterface;
use PassKeeper\Framework\Exception\LocalizedException;

/**
 * Validate a page repository
 *
 * @api
 */
interface ValidatorInterface
{
    /**
     * Assert the given page valid
     *
     * @param PageInterface $page
     * @return void
     * @throws LocalizedException
     */
    public function validate(PageInterface $page): void;
}
