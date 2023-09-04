<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace PassKeeper\Cms\Model\Wysiwyg\Images\Storage;

use PassKeeper\Framework\App\Filesystem\DirectoryList;
use PassKeeper\Framework\Exception\FileSystemException;

/**
 * Wysiwyg Images storage collection
 *
 * @api
 * @since 100.0.2
 */
class Collection extends \PassKeeper\Framework\Data\Collection\Filesystem
{
    /**
     * @var \PassKeeper\Framework\Filesystem
     */
    protected $_filesystem;

    /**
     * @param \PassKeeper\Framework\Data\Collection\EntityFactory $entityFactory
     * @param \PassKeeper\Framework\Filesystem $filesystem
     */
    public function __construct(
        \PassKeeper\Framework\Data\Collection\EntityFactory $entityFactory,
        \PassKeeper\Framework\Filesystem $filesystem
    ) {
        $this->_filesystem = $filesystem;
        parent::__construct($entityFactory, $filesystem);
    }

    /**
     * Generate row
     *
     * @param string $filename
     * @return array
     */
    protected function _generateRow($filename)
    {
        $filename = $filename !== null ?
            preg_replace('~[/\\\]+(?<![htps?]://)~', '/', $filename) : '';
        $path = $this->_filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        try {
            $mtime = $path->stat($path->getRelativePath($filename))['mtime'];
        } catch (FileSystemException $e) {
            $mtime = 0;
        }
        return [
            'filename' => rtrim($filename, '/'),
            // phpcs:ignore PassKeeper2.Functions.DiscouragedFunction
            'basename' => basename($filename),
            'mtime' => $mtime
        ];
    }
}
