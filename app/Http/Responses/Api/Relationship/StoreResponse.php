<?php

namespace App\Http\Responses\Api\Relationship;

use App\Exceptions\ValidationHttpException;
use App\Http\Responses\BaseResponse;
use App\Services\RelationshipService;
use App\Services\UserService;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Collection;

class StoreResponse extends BaseResponse implements Responsable
{

    /**
     * @var RelationshipService
     */
    private $relationshipService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var Collection
     */
    private $friends;

    public function __construct(
        RelationshipService $relationshipService,
        UserService $userService
    ) {
        $this->relationshipService = $relationshipService;
        $this->userService = $userService;
    }

    public function toResponse($request)
    {
        $request = $this->getRequest();

        $this->validation($request);

        $userIds = [];
        foreach ($this->friends as $user) {
            $userIds[] = $user->id;
        }

        $this->relationshipService->store($userIds);

        return ['success' => true];
    }

    /**
     * @param $request
     */
    private function validation($request)
    {
        $validator = \Validator::make($request->all(), [
            'friends' => 'required|array|min:2|max:2',
        ]);
        if ($validator->fails()) {
            $this->errorBadRequest($validator);
        }

        $friends = $request->get('friends');
        if (!isset($friends[0]) || !isset($friends[1]) || $friends[0] === $friends[1]) {
            throw new ValidationHttpException([self::EMAILS_ARE_REQUIRED]);
        }

        $this->friends = $this->userService->findEmail($request->get('friends'));
        if (count($this->friends) !== self::NUMBER_OF_EMAILS_REQUIRED) {
            throw new ValidationHttpException([self::EMAILS_ARE_REQUIRED]);
        }
    }
}