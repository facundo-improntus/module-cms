<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace PassKeeper\Cms\Model\Template;

/**
 * Class Cms Template Filter Provider
 *
 * @api
 */
class FilterProvider
{
    /**
     * @var \PassKeeper\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var string
     */
    protected $_pageFilter;

    /**
     * @var string
     */
    protected $_blockFilter;

    /**
     * @var array
     */
    protected $_instanceList;

    /**
     * @param \PassKeeper\Framework\ObjectManagerInterface $objectManager
     * @param string $pageFilter
     * @param string $blockFilter
     */
    public function __construct(
        \PassKeeper\Framework\ObjectManagerInterface $objectManager,
        $pageFilter = \PassKeeper\Cms\Model\Template\Filter::class,
        $blockFilter = \PassKeeper\Cms\Model\Template\Filter::class
    ) {
        $this->_objectManager = $objectManager;
        $this->_pageFilter = $pageFilter;
        $this->_blockFilter = $blockFilter;
    }

    /**
     * @param string $instanceName
     * @return \PassKeeper\Framework\Filter\Template
     * @throws \Exception
     */
    protected function _getFilterInstance($instanceName)
    {
        if (!isset($this->_instanceList[$instanceName])) {
            $instance = $this->_objectManager->get($instanceName);

            if (!$instance instanceof \PassKeeper\Framework\Filter\Template) {
                throw new \Exception('Template filter ' . $instanceName . ' does not implement required interface');
            }
            $this->_instanceList[$instanceName] = $instance;
        }

        return $this->_instanceList[$instanceName];
    }

    /**
     * @return \PassKeeper\Framework\Filter\Template
     */
    public function getBlockFilter()
    {
        return $this->_getFilterInstance($this->_blockFilter);
    }

    /**
     * @return \PassKeeper\Framework\Filter\Template
     */
    public function getPageFilter()
    {
        return $this->_getFilterInstance($this->_pageFilter);
    }
}
