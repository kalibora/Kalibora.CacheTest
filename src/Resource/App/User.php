<?php
namespace Kalibora\CacheTest\Resource\App;

use BEAR\Package\Annotation\ReturnCreatedResource;
use BEAR\Resource\Code;
use BEAR\Resource\Exception\ResourceNotFoundException;
use BEAR\Resource\ResourceObject;
use Kalibora\CacheTest\EntityRepository\UserRepository;

class User extends ResourceObject
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function onGet(string $id) : ResourceObject
    {
        $user = $this->repository->find($id);

        if ($user === null) {
            throw new ResourceNotFoundException();
        }

        $this->body = $user;

        return $this;
    }

    /**
     * @ReturnCreatedResource
     */
    public function onPost(string $name) : ResourceObject
    {
        $user = [
            'name' => $name,
        ];

        $id = $this->repository->save($user);

        $this->code = Code::CREATED;
        $this->headers['Location'] = "/user?id={$id}";

        return $this;
    }

    public function onPut(string $id, string $name) : ResourceObject
    {
        $user = $this->repository->find($id);

        if ($user === null) {
            throw new ResourceNotFoundException();
        }

        $user['name'] = $name;

        $this->repository->save($user);

        $this->code = Code::NO_CONTENT;

        return $this;
    }

    public function onDelete(string $id) : ResourceObject
    {
        $user = $this->repository->find($id);

        if ($user === null) {
            throw new ResourceNotFoundException();
        }

        $this->repository->delete($id);

        $this->code = Code::NO_CONTENT;

        return $this;
    }
}
