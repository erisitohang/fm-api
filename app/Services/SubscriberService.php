<?php

namespace App\Services;


use App\Repositories\Contracts\SubscribeRepositoryInterface;

class SubscriberService
{

    /**
     * @var SubscribeRepositoryInterface
     */
    private $subscribeRepository;

    /**
     * SubscriberService constructor.
     * @param SubscribeRepositoryInterface $subscribeRepository
     */
    public function __construct(SubscribeRepositoryInterface $subscribeRepository)
    {
        $this->subscribeRepository = $subscribeRepository;
    }

    /**
     * @param int $userId
     * @param int $targetId
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function subscribe($userId, $targetId)
    {
        return $this->createOrUpdate($userId, $targetId, 'is_subscribe');
    }

    /**
     * @param int $userId
     * @param int $targetId
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function block($userId, $targetId)
    {
        return $this->createOrUpdate($userId, $targetId, 'is_block');
    }

    /**
     * @param int $userId
     * @param int $targetId
     * @param string $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private function createOrUpdate($userId, $targetId, $field)
    {
        $data = [
            'user_id' => $userId,
            'target_id' => $targetId
        ];

        $subscriber = $this->subscribeRepository->findOneBy($data);

        if ($subscriber && $subscriber->id) {
            $data = [
                $field => true
            ];
            $this->subscribeRepository->update($subscriber, $data);
        } else {
            $data[$field] =  true;
            $subscriber =  $this->subscribeRepository->save($data);
        }

        return $subscriber;
    }
}