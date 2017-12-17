<?php
namespace Kalibora\CacheTest\Resource\App;

use BEAR\Resource\ResourceObject;
use BEAR\RepositoryModule\Annotation\Cacheable;
use Koriym\HttpConstants\StatusCode;

/**
 * @Cacheable(expirySecond=5)
 */
class User extends ResourceObject
{
    private static $users = [
        1 => [
            'id' => 1,
            'name' => 'foo',
        ],
        2 => [
            'id' => 2,
            'name' => 'bar',
        ],
    ];

    public function onGet(string $id) : ResourceObject
    {
        $user = self::$users[$id] ?? null;

        if ($user === null) {
            $this->code = StatusCode::NOT_FOUND;
            return $this;
        }

        $this->body = $user;

        return $this;
    }
}
