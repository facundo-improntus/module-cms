<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Observer;

use PassKeeper\Framework\Event\ObserverInterface;

class NoRouteObserver implements ObserverInterface
{
    /**
     * Modify No Route Forward object
     *
     * @param \PassKeeper\Framework\Event\Observer $observer
     * @return self
     */
    public function execute(\PassKeeper\Framework\Event\Observer $observer)
    {
        $observer->getEvent()->getStatus()->setLoaded(
            true
        )->setForwardModule(
            'cms'
        )->setForwardController(
            'index'
        )->setForwardAction(
            'noroute'
        );

        return $this;
    }
}
