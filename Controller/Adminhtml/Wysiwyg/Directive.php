<?php
/**
 *
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace PassKeeper\Cms\Controller\Adminhtml\Wysiwyg;

use PassKeeper\Backend\App\Action;
use PassKeeper\Cms\Model\Template\Filter;
use PassKeeper\Cms\Model\Wysiwyg\Config;
use PassKeeper\Framework\App\Action\HttpGetActionInterface;
use PassKeeper\Framework\App\Filesystem\DirectoryList;
use PassKeeper\Framework\Filesystem;
use PassKeeper\Framework\Image\Adapter\AdapterInterface;
use PassKeeper\Framework\Image\AdapterFactory;
use Psr\Log\LoggerInterface;
use PassKeeper\Framework\Url\DecoderInterface;
use PassKeeper\Framework\Controller\Result\Raw;
use PassKeeper\Framework\Controller\Result\RawFactory;
use PassKeeper\Backend\App\Action\Context;
use PassKeeper\Framework\App\ObjectManager;
use PassKeeper\Framework\Filesystem\Driver\File;

/**
 * Process template text for wysiwyg editor.
 *
 * Class Directive
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) usage of $this->file eliminated, but it's still there due to BC
 */
class Directive extends Action implements HttpGetActionInterface
{

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'PassKeeper_Cms::media_gallery';

    /**
     * @var DecoderInterface
     */
    protected $urlDecoder;

    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var AdapterFactory
     */
    private $adapterFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var File
     * @deprecated use $filesystem instead
     */
    private $file;
    /**
     * @var Filesystem|null
     */
    private $filesystem;

    /**
     * Constructor
     *
     * @param Context $context
     * @param DecoderInterface $urlDecoder
     * @param RawFactory $resultRawFactory
     * @param AdapterFactory|null $adapterFactory
     * @param LoggerInterface|null $logger
     * @param Config|null $config
     * @param Filter|null $filter
     * @param File|null $file
     * @param Filesystem|null $filesystem
     */
    public function __construct(
        Context $context,
        DecoderInterface $urlDecoder,
        RawFactory $resultRawFactory,
        AdapterFactory $adapterFactory = null,
        LoggerInterface $logger = null,
        Config $config = null,
        Filter $filter = null,
        File $file = null,
        Filesystem $filesystem = null
    ) {
        parent::__construct($context);
        $this->urlDecoder = $urlDecoder;
        $this->resultRawFactory = $resultRawFactory;
        $this->adapterFactory = $adapterFactory ?: ObjectManager::getInstance()->get(AdapterFactory::class);
        $this->logger = $logger ?: ObjectManager::getInstance()->get(LoggerInterface::class);
        $this->config = $config ?: ObjectManager::getInstance()->get(Config::class);
        $this->filter = $filter ?: ObjectManager::getInstance()->get(Filter::class);
        $this->file = $file ?: ObjectManager::getInstance()->get(File::class);
        $this->filesystem = $filesystem ?: ObjectManager::getInstance()->get(Filesystem::class);
    }

    /**
     * Template directives callback
     *
     * @return Raw
     * @throws \PassKeeper\Framework\Exception\FileSystemException
     */
    public function execute()
    {
        $directive = $this->getRequest()->getParam('___directive');
        $directive = $this->urlDecoder->decode($directive);
        $image = null;
        $resultRaw = null;
        try {
            /** @var Filter $filter */
            $imagePath = $this->filter->filter($directive);
            /** @var AdapterInterface $image */
            $image = $this->adapterFactory->create();
            /** @var Raw $resultRaw */
            $resultRaw = $this->resultRawFactory->create();
            $image->open($imagePath);
            $resultRaw->setHeader('Content-Type', $image->getMimeType());
            $resultRaw->setContents($image->getImage());
        } catch (\Exception $e) {
            /** @var Config $config */
            $imagePath = $this->config->getSkinImagePlaceholderPath();
            try {
                $image->open($imagePath);
                $resultRaw->setHeader('Content-Type', $image->getMimeType());
                $resultRaw->setContents($image->getImage());
                $this->logger->warning($e);
            } catch (\Exception $e) {
                $this->logger->warning($e);
            }
        }
        $mimeType = $image->getMimeType();
        unset($image);
        // To avoid issues with PNG images with alpha blending we return raw file
        // after validation as an image source instead of generating the new PNG image
        // with image adapter
        $content = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA)->getDriver()
            ->fileGetContents($imagePath);
        $resultRaw->setHeader('Content-Type', $mimeType);
        $resultRaw->setContents($content);

        return $resultRaw;
    }
}
