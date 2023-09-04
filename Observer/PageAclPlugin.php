<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace PassKeeper\Cms\Observer;

use PassKeeper\Cms\Api\Data\PageInterface;
use PassKeeper\Cms\Api\PageRepositoryInterface;
use PassKeeper\Cms\Model\Page\Authorization;

/**
 * Perform additional authorization before saving a page.
 */
class PageAclPlugin
{
    /**
     * @var Authorization
     */
    private $authorization;

    /**
     * @param Authorization $authorization
     */
    public function __construct(Authorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * Authorize saving before it is executed.
     *
     * @param PageRepositoryInterface $subject
     * @param PageInterface $page
     * @return array
     * @throws \PassKeeper\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSave(PageRepositoryInterface $subject, PageInterface $page): array
    {
        $this->authorization->authorizeFor($page);

        return [$page];
    }
}
