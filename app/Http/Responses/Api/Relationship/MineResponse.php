<?php

namespace App\Http\Responses\Api\Relationship;

use App\Exceptions\ValidationHttpException;
use App\Http\Responses\BaseResponse;
use App\Models\User;
use App\Services\RelationshipService;
use App\Services\UserService;
use App\Transformers\RelationshipTransformer;
use Illuminate\Contracts\Support\Responsable;

class MineResponse extends BaseResponse implements Responsable
{

    const EMAILS_ARE_REQUIRED = 'Emails are required';

    /**
     * @var RelationshipService
     */
    private $relationshipService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var User
     */
    private $user;

    /**
     * @var RelationshipTransformer
     */
    private $relationshipTransformer;

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

        $email = $request->get(self::EMAIL);

        $collection = $this->relationshipService->myFriends($this->user->id);

        $friendList =  $this->relationshipTransformer->transformCollection($collection);

        $friends = array_map(function($friend) use ($email) {
            return $friend[RelationshipService::USER_ONE_EMAIL] === $email ?
                $friend[RelationshipService::USER_TWO_EMAIL] : $friend[RelationshipService::USER_ONE_EMAIL];
        }, $friendList);

        return [
            self::SUCCESS => true,
            self::FRIENDS => $friends,
            self::COUNT =>  count($friends)
        ];
    }

    /**
     * @param $request
     */
    private function validation($request)
    {
        $validator = \Validator::make($request->all(), [
            self::EMAIL => 'required|email',
        ]);
        if ($validator->fails()) {
            $this->errorBadRequest($validator);
        }
        $this->user = $this->userService->findByEmail($request->get(self::EMAIL));
        if (!$this->user) {
            throw new ValidationHttpException([self::EMAILS_ARE_REQUIRED]);
        }
    }
}