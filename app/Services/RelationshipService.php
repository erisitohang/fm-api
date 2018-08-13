<?php

namespace App\Services;


use App\Repositories\Contracts\RelationshipRepositoryInterface;

class RelationshipService
{
    const USER_ONE_ID = 'user_one_id';
    const USER_TWO_ID = 'user_two_id';
    const USER_ONE_EMAIL = 'user_one_email';
    const USER_TWO_EMAIL = 'user_two_email';

    /**
     * @var RelationshipRepositoryInterface
     */
    private $relationshipRepository;

    /**
     * RelationshipService constructor.
     * @param RelationshipRepositoryInterface $relationshipRepository
     */
    public function __construct(RelationshipRepositoryInterface $relationshipRepository)
    {
        $this->relationshipRepository = $relationshipRepository;
    }

    /**
     * @param array $userIds
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store($userIds)
    {
        $userOneId = $userIds[0];
        $userTwoId = $userIds[1];
        if ($userOneId > $userTwoId) {
            $userOneId = $userIds[1];
            $userTwoId = $userIds[0];
        }

        $data = [
            self::USER_ONE_ID => $userOneId,
            self::USER_TWO_ID => $userTwoId,
        ];

        return $this->relationshipRepository->save($data);
    }

    /**
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function myFriends($userId)
    {
        return $this->relationshipRepository->findUserId($userId);
    }
}
