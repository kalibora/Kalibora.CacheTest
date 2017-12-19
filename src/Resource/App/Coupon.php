<?php
namespace Kalibora\CacheTest\Resource\App;

use BEAR\Package\Annotation\ReturnCreatedResource;
use BEAR\Resource\Code;
use BEAR\Resource\Exception\BadRequestException;
use BEAR\Resource\Exception\ResourceNotFoundException;
use BEAR\Resource\ResourceObject;
use Kalibora\CacheTest\EntityRepository\CouponRepository;

class Coupon extends ResourceObject
{
    private $repository;

    public function __construct(CouponRepository $repository)
    {
        $this->repository = $repository;
    }

    public function onGet(string $id) : ResourceObject
    {
        $coupon = $this->repository->find($id);

        if ($coupon === null) {
            throw new ResourceNotFoundException();
        }

        $now = new \DateTimeImmutable();
        if ($coupon['expiredAt'] < $now) {
            $coupon['isExpired'] = true;
        } else {
            $coupon['isExpired'] = false;
        }

        $this->body = $coupon;

        return $this;
    }

    /**
     * @ReturnCreatedResource
     */
    public function onPost(string $name, string $expiredAt) : ResourceObject
    {
        $coupon = [
            'name'      => $name,
            'expiredAt' => $this->convertExpiredAt($expiredAt),
        ];

        $id = $this->repository->save($coupon);

        $this->code = Code::CREATED;
        $this->headers['Location'] = "/coupon?id={$id}";

        return $this;
    }

    public function onPut(string $id, string $name, string $expiredAt) : ResourceObject
    {
        $coupon = $this->repository->find($id);

        if ($coupon === null) {
            throw new ResourceNotFoundException();
        }

        $coupon['name'] = $name;
        $coupon['expiredAt'] = $this->convertExpiredAt($expiredAt);

        $this->repository->save($coupon);

        $this->code = Code::NO_CONTENT;

        return $this;
    }

    public function onDelete(string $id) : ResourceObject
    {
        $coupon = $this->repository->find($id);

        if ($coupon === null) {
            throw new ResourceNotFoundException();
        }

        $this->repository->delete($id);

        $this->code = Code::NO_CONTENT;

        return $this;
    }

    private function convertExpiredAt(string $expiredAt)
    {
        try {
            $expiredAt = new \DateTimeImmutable($expiredAt);
        } catch (\Exception $e) {
            throw new BadRequestException();
        }

        return $expiredAt;
    }
}
