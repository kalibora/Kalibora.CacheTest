<?php

namespace Kalibora\CacheTest\Provide\StorageFilesystem;

use Doctrine\Common\Cache\FilesystemCache;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

class StorageFilesystemCacheProvider implements ProviderInterface
{
    private $directory;

    /**
     * @Named("storage_filesystem_directory")
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $filesystemCache = new FilesystemCache($this->directory);

        return $filesystemCache;
    }
}
