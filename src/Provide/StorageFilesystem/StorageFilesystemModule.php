<?php

namespace Kalibora\CacheTest\Provide\StorageFilesystem;

use BEAR\RepositoryModule\Annotation\Storage;
use Doctrine\Common\Cache\CacheProvider;
use Ray\Di\AbstractModule;

class StorageFilesystemModule extends AbstractModule
{
    private $directory;

    public function __construct(string $directory, AbstractModule $module = null)
    {
        $this->directory = $directory;
        parent::__construct($module);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind()->annotatedWith('storage_filesystem_directory')->toInstance($this->directory);
        $this->bind(CacheProvider::class)->annotatedWith(Storage::class)->toProvider(StorageFilesystemCacheProvider::class);
    }
}
