<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace PassKeeper\Cms\Observer;

use PassKeeper\Cms\Api\Data\PageInterface;
use PassKeeper\Cms\Model\Page\Authorization;
use PassKeeper\Framework\Event\Observer;
use PassKeeper\Framework\Event\ObserverInterface;
use PassKeeper\Framework\Exception\LocalizedException;

/**
 * Performing additional validation each time a user saves a CMS page.
 */
class PageValidatorObserver implements ObserverInterface
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
     * @inheritDoc
     *
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        /** @var PageInterface $page */
        $page = $observer->getEvent()->getData('page');
        $this->authorization->authorizeFor($page);
    }
}
