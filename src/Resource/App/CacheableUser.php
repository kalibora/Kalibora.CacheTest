<?php
namespace Kalibora\CacheTest\Resource\App;

use BEAR\Package\Annotation\ReturnCreatedResource;
use BEAR\RepositoryModule\Annotation\Cacheable;

/**
 * @Cacheable
 */
class CacheableUser extends User
{
}
