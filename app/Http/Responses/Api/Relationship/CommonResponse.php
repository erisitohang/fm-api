<?php

namespace App\Http\Responses\Api\Relationship;

use App\Exceptions\ValidationHttpException;
use App\Http\Responses\BaseResponse;
use App\Services\RelationshipService;
use App\Services\UserService;
use App\Transformers\RelationshipTransformer;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Collection;

class CommonResponse extends BaseResponse implements Responsable
{
    const FRIENDSHIPS = 'friendships';

    /**
     * @var RelationshipService
     */
    private $relationshipService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var RelationshipTransformer
     */
    private $relationshipTransformer;

    /**
     * @var Collection
     */
    private $friends;

    public function __construct(
        RelationshipService $relationshipService,
        UserService $userService,
        RelationshipTransformer $relationshipTransformer
    ) {
        $this->relationshipService = $relationshipService;
        $this->userService = $userService;
        $this->relationshipTransformer = $relationshipTransformer;
    }

    public function toResponse($request)
    {
        $request = $this->getRequest();

        $this->validation($request);

        $userIds = [];
        foreach ($this->friends as $user) {
            $userIds[] = $user->id;
        }

        $user1[self::FRIENDSHIPS] = $this->relationshipService->myFriends($userIds[0]);
        $user1[RelationshipService::USER_ONE_ID] =
            $user1[self::FRIENDSHIPS]->pluck(RelationshipService::USER_ONE_ID)->all();
        $user1[RelationshipService::USER_TWO_ID] =
            $user1[self::FRIENDSHIPS]->pluck(RelationshipService::USER_TWO_ID)->all();

        $user2[self::FRIENDSHIPS] = $this->relationshipService->myFriends($userIds[1]);
        $user2[RelationshipService::USER_ONE_ID] =
            $user2[self::FRIENDSHIPS]->pluck(RelationshipService::USER_ONE_ID)->all();
        $user2[RelationshipService::USER_TWO_ID] =
            $user2[self::FRIENDSHIPS]->pluck(RelationshipService::USER_TWO_ID)->all();

        $mutualFriendships = array_unique(
            array_intersect(
                array_merge(
                    $user1[RelationshipService::USER_ONE_ID],
                    $user1[RelationshipService::USER_TWO_ID]
                ),
                array_merge(
                    $user2[RelationshipService::USER_ONE_ID],
                    $user2[RelationshipService::USER_TWO_ID]
                )
            )
        );

        $users = $this->userService->inAndNotIn($mutualFriendships, $userIds);
        $emails = $users->pluck(self::EMAIL)->all();
        return [
            self::SUCCESS  => true,
            self::FRIENDS => $emails,
            self::COUNT =>  count($emails)
        ];
    }

    /**
     * @param $request
     */
    private function validation($request)
    {
        $validator = \Validator::make($request->all(), [
            self::FRIENDS => 'required|array|min:2|max:2',
        ]);
        if ($validator->fails()) {
            $this->errorBadRequest($validator);
        }

        $friends = $request->get(self::FRIENDS);
        if (!isset($friends[0]) || !isset($friends[1]) || $friends[0] === $friends[1]) {
            throw new ValidationHttpException([self::EMAILS_ARE_REQUIRED]);
        }

        $this->friends = $this->userService->findEmail($request->get(self::FRIENDS));
        if (count($this->friends) !== self::NUMBER_OF_EMAILS_REQUIRED) {
            throw new ValidationHttpException([self::EMAILS_ARE_REQUIRED]);
        }
    }
}
