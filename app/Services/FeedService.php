<?php

namespace App\Services;

use App\Repositories\Contracts\SubscribeRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class FeedService
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var RelationshipService
     */
    private $relationshipService;

    /**
     * @var SubscribeRepositoryInterface
     */
    private $subscribeRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        RelationshipService $relationshipService,
        SubscribeRepositoryInterface $subscribeRepository
    ) {
        $this->userRepository = $userRepository;
        $this->relationshipService = $relationshipService;
        $this->subscribeRepository = $subscribeRepository;
    }

    public function recipients($userId, $text)
    {
        $mentionIds = $this->getMention($text);

        $friendIds = $this->getFriends($userId);
        list($subscribers, $blockers) = $this->getSubscribersBlockers($userId);

        $list = array_unique(
                array_merge(
                    $mentionIds,
                    $friendIds,
                    $subscribers
                )
        );

        return $this->userRepository->findIn('id', array_diff($list, $blockers));
    }

    /**
     * @param $userId
     * @return array
     */
    private function getSubscribersBlockers($userId)
    {
        $target = [
            'target_id' => $userId
        ];

        $listOfSubscribers = $this->subscribeRepository->findBy($target);

        $subscribers = [];
        $blockers = [];
        if ($listOfSubscribers) {
            foreach ($listOfSubscribers as $subscriber) {
                if ($subscriber->is_block) {
                    $blockers[] = $subscriber->user_id;
                } elseif (!$subscriber->is_block && $subscriber->is_subscribe) {
                    $subscribers[] = $subscriber->user_id;
                }
            }

        }

        return [$subscribers, $blockers];
    }

    /**
     * @param $userId
     * @return array
     */
    private function getFriends($userId): array
    {
        $myFriendList = $this->relationshipService->myFriends($userId);
        $user1 = $myFriendList->pluck(RelationshipService::USER_ONE_ID)->all();
        $user2 = $myFriendList->pluck(RelationshipService::USER_TWO_ID)->all();
        $friendIds = array_unique(array_merge(
            $user1,
            $user2
        ));

        $friendIds = array_diff($friendIds, [$userId]);

        return $friendIds;
    }

    /**
     * @param $text
     * @param $matches
     * @return array
     */
    private function getMention($text): array
    {
        preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $text, $matches);
        $emails = $matches[0];
        $mentionUsers = $this->userRepository->findIn('email', $emails);
        $mentionIds = $mentionUsers->pluck('id')->all();

        return $mentionIds;
    }

}