<?php

namespace Kalibora\CacheTest\EntityRepository;

use Doctrine\Common\Cache\Cache;
use BEAR\RepositoryModule\Annotation\Storage;

abstract class AbstractEntityRepository
{
    private $storage;

    /**
     * @Storage
     */
    public function __construct(Cache $storage)
    {
        $this->storage = $storage;
    }

    public function save(array $entity)
    {
        if (isset($entity['id'])) {
            $id = $entity['id'];
        } else {
            $id = $this->nextId();
            $entity['id'] = $id;
        }

        $this->storage->save($this->idKey($id), $entity);

        return $id;
    }

    public function find($id)
    {
        $key = $this->idKey($id);

        if ($this->storage->contains($key)) {
            return $this->storage->fetch($key);
        }

        return null;
    }

    public function delete($id)
    {
        $this->storage->delete($this->idKey($id));
    }

    private function nextId()
    {
        if ($this->storage->contains($this->lastIdKey())) {
            $nextId = $this->storage->fetch($this->lastIdKey());
            $nextId++;
        } else {
            $nextId = 1;
        }

        $this->storage->save($this->lastIdKey(), $nextId);

        return $nextId;
    }

    private function idKey($id)
    {
        return get_class($this) . '_id_' . $id;
    }

    private function lastIdKey()
    {
        return get_class($this) . '_lastid';
    }
}
