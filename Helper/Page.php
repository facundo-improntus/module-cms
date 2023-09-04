<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PassKeeper\Cms\Helper;

use PassKeeper\Cms\Model\Page\CustomLayoutManagerInterface;
use PassKeeper\Cms\Model\Page\CustomLayoutRepositoryInterface;
use PassKeeper\Cms\Model\Page\IdentityMap;
use PassKeeper\Framework\App\ActionInterface;
use PassKeeper\Framework\App\Helper\AbstractHelper;
use PassKeeper\Framework\App\ObjectManager;
use PassKeeper\Framework\Exception\NoSuchEntityException;
use PassKeeper\Framework\View\Result\Page as ResultPage;

/**
 * CMS Page Helper
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class Page extends AbstractHelper
{
    /**
     * CMS no-route config path
     */
    public const XML_PATH_NO_ROUTE_PAGE = 'web/default/cms_no_route';

    /**
     * CMS no cookies config path
     */
    public const XML_PATH_NO_COOKIES_PAGE = 'web/default/cms_no_cookies';

    /**
     * CMS home page config path
     */
    public const XML_PATH_HOME_PAGE = 'web/default/cms_home_page';

    /**
     * Design package instance
     *
     * @var \PassKeeper\Framework\View\DesignInterface
     */
    protected $_design;

    /**
     * @var \PassKeeper\Cms\Model\Page
     */
    protected $_page;

    /**
     * @var \PassKeeper\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \PassKeeper\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_localeDate;

    /**
     * @var \PassKeeper\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \PassKeeper\Cms\Model\PageFactory
     */
    protected $_pageFactory;

    /**
     * @var \PassKeeper\Framework\Escaper
     */
    protected $_escaper;

    /**
     * @var \PassKeeper\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var CustomLayoutManagerInterface
     */
    private $customLayoutManager;

    /**
     * @var CustomLayoutRepositoryInterface
     */
    private $customLayoutRepo;

    /**
     * @var IdentityMap
     */
    private $identityMap;

    /**
     * Constructor
     *
     * @param \PassKeeper\Framework\App\Helper\Context $context
     * @param \PassKeeper\Framework\Message\ManagerInterface $messageManager
     * @param \PassKeeper\Cms\Model\Page $page
     * @param \PassKeeper\Framework\View\DesignInterface $design
     * @param \PassKeeper\Cms\Model\PageFactory $pageFactory
     * @param \PassKeeper\Store\Model\StoreManagerInterface $storeManager
     * @param \PassKeeper\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \PassKeeper\Framework\Escaper $escaper
     * @param \PassKeeper\Framework\View\Result\PageFactory $resultPageFactory
     * @param CustomLayoutManagerInterface|null $customLayoutManager
     * @param CustomLayoutRepositoryInterface|null $customLayoutRepo
     * @param IdentityMap|null $identityMap
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \PassKeeper\Framework\App\Helper\Context $context,
        \PassKeeper\Framework\Message\ManagerInterface $messageManager,
        \PassKeeper\Cms\Model\Page $page,
        \PassKeeper\Framework\View\DesignInterface $design,
        \PassKeeper\Cms\Model\PageFactory $pageFactory,
        \PassKeeper\Store\Model\StoreManagerInterface $storeManager,
        \PassKeeper\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \PassKeeper\Framework\Escaper $escaper,
        \PassKeeper\Framework\View\Result\PageFactory $resultPageFactory,
        ?CustomLayoutManagerInterface $customLayoutManager = null,
        ?CustomLayoutRepositoryInterface $customLayoutRepo = null,
        ?IdentityMap $identityMap = null
    ) {
        $this->messageManager = $messageManager;
        $this->_page = $page;
        $this->_design = $design;
        $this->_pageFactory = $pageFactory;
        $this->_storeManager = $storeManager;
        $this->_localeDate = $localeDate;
        $this->_escaper = $escaper;
        $this->resultPageFactory = $resultPageFactory;
        $this->customLayoutManager = $customLayoutManager
            ?? ObjectManager::getInstance()->get(CustomLayoutManagerInterface::class);
        $this->customLayoutRepo = $customLayoutRepo
            ?? ObjectManager::getInstance()->get(CustomLayoutRepositoryInterface::class);
        $this->identityMap = $identityMap ?? ObjectManager::getInstance()->get(IdentityMap::class);
        parent::__construct($context);
    }

    /**
     * Return result CMS page
     *
     * @param ActionInterface $action
     * @param int $pageId
     * @return ResultPage|bool
     */
    public function prepareResultPage(ActionInterface $action, $pageId = null)
    {
        if ($pageId !== null && $pageId !== $this->_page->getId()) {
            $delimiterPosition = strrpos((string)$pageId, '|');
            if ($delimiterPosition) {
                $pageId = substr($pageId, 0, $delimiterPosition);
            }

            $this->_page->setStoreId($this->_storeManager->getStore()->getId());
            if (!$this->_page->load($pageId)) {
                return false;
            }
        }

        if (!$this->_page->getId()) {
            return false;
        }
        $this->identityMap->add($this->_page);

        $inRange = $this->_localeDate->isScopeDateInInterval(
            null,
            $this->_page->getCustomThemeFrom(),
            $this->_page->getCustomThemeTo()
        );

        if ($this->_page->getCustomTheme()) {
            if ($inRange) {
                $this->_design->setDesignTheme($this->_page->getCustomTheme());
            }
        }
        /** @var ResultPage $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->setLayoutType($inRange, $resultPage);
        $resultPage->addHandle('cms_page_view');
        $pageHandles = [
            'id' => $this->_page->getIdentifier() === null ? '' : str_replace('/', '_', $this->_page->getIdentifier())
        ];
        //Selected custom updates.
        try {
            $this->customLayoutManager->applyUpdate(
                $resultPage,
                $this->customLayoutRepo->getFor($this->_page->getId())
            );
            // phpcs:disable PassKeeper2.CodeAnalysis.EmptyBlock.DetectedCatch
        } catch (NoSuchEntityException $exception) {
            //No custom layout selected
        }

        $resultPage->addPageLayoutHandles($pageHandles);

        $this->_eventManager->dispatch(
            'cms_page_render',
            ['page' => $this->_page, 'controller_action' => $action, 'request' => $this->_getRequest()]
        );

        if ($this->_page->getCustomLayoutUpdateXml() && $inRange) {
            $layoutUpdate = $this->_page->getCustomLayoutUpdateXml();
        } else {
            $layoutUpdate = $this->_page->getLayoutUpdateXml();
        }
        if (!empty($layoutUpdate)) {
            $resultPage->getLayout()->getUpdate()->addUpdate($layoutUpdate);
        }

        $contentHeadingBlock = $resultPage->getLayout()->getBlock('page_content_heading');
        if ($contentHeadingBlock) {
            $contentHeading = $this->_escaper->escapeHtml($this->_page->getContentHeading());
            $contentHeadingBlock->setContentHeading($contentHeading);
        }

        return $resultPage;
    }

    /**
     * Retrieve page direct URL
     *
     * @param string $pageId
     * @return string
     */
    public function getPageUrl($pageId = null)
    {
        /** @var \PassKeeper\Cms\Model\Page $page */
        $page = $this->_pageFactory->create();
        if ($pageId !== null) {
            $page->setStoreId($this->_storeManager->getStore()->getId());
            $page->load($pageId);
        }

        if (!$page->getId()) {
            return null;
        }

        return $this->_urlBuilder->getUrl(null, ['_direct' => $page->getIdentifier()]);
    }

    /**
     * Set layout type
     *
     * @param bool $inRange
     * @param ResultPage $resultPage
     * @return ResultPage
     */
    protected function setLayoutType($inRange, $resultPage)
    {
        if ($this->_page->getPageLayout()) {
            if ($this->_page->getCustomPageLayout()
                && $this->_page->getCustomPageLayout() != 'empty'
                && $inRange
            ) {
                $handle = $this->_page->getCustomPageLayout();
            } else {
                $handle = $this->_page->getPageLayout();
            }
            $resultPage->getConfig()->setPageLayout($handle);
        }
        return $resultPage;
    }
}
