<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace PassKeeper\Cms\Model\Wysiwyg;

/**
 * Class DefaultConfigProvider returns data required to render tinymce editor
 */
class DefaultConfigProvider implements \PassKeeper\Framework\Data\Wysiwyg\ConfigProviderInterface
{
    /**
     * @var \PassKeeper\Framework\View\Asset\Repository
     */
    private $assetRepo;

    /**
     * @param \PassKeeper\Framework\View\Asset\Repository $assetRepo
     */
    public function __construct(\PassKeeper\Framework\View\Asset\Repository $assetRepo)
    {
        $this->assetRepo = $assetRepo;
    }

    /**
     * @inheritdoc
     */
    public function getConfig(\PassKeeper\Framework\DataObject $config) : \PassKeeper\Framework\DataObject
    {
        $config->addData([
            'tinymce' => [
                'toolbar' => 'formatselect | bold italic underline | alignleft aligncenter alignright | '
                    . 'bullist numlist | link table charmap',
                'plugins' => implode(
                    ' ',
                    [
                        'advlist',
                        'autolink',
                        'lists',
                        'link',
                        'charmap',
                        'media',
                        'noneditable',
                        'table',
                        'paste',
                        'code',
                        'help',
                        'table'
                    ]
                ),
                'content_css' => $this->assetRepo->getUrl('mage/adminhtml/wysiwyg/tiny_mce/themes/ui.css')
            ]
        ]);
        return $config;
    }
}
