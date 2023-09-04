<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace PassKeeper\Cms\Model\Wysiwyg;

use PassKeeper\Framework\Data\Wysiwyg\ConfigProviderInterface as WysiwygConfigInterface;

/**
 * Class ConfigProviderFactory to create config provider object by class name
 */
class ConfigProviderFactory
{
    /**
     * Object manager
     *
     * @var \PassKeeper\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param \PassKeeper\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\PassKeeper\Framework\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Create config provider instance
     *
     * @param string $instance
     * @param array $arguments
     * @return \PassKeeper\Framework\Data\Wysiwyg\ConfigProviderInterface
     */
    public function create(string $instance, array $arguments = []): WysiwygConfigInterface
    {
        return $this->objectManager->create($instance, $arguments);
    }
}
