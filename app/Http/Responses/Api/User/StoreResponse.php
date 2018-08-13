<?php

namespace App\Http\Responses\Api\User;

use App\Http\Responses\BaseResponse;
use App\Models\User;
use App\Services\UserService;
use App\Transformers\UserTransformer;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Validation\ValidationException;

class StoreResponse extends BaseResponse implements Responsable
{

    /**
     * @var UserService
     */
    private $userService;

    /**
     * StoreResponse constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService =$userService;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response
     * @throws ValidationException
     */
    public function toResponse($request)
    {
        $request = $this->getRequest();

        $this->validation($request);

        $data = [
            self::EMAIL => $request->get(self::EMAIL),
            self::NAME => $request->get(self::NAME),
        ];

        /** @var User $user */
        $user = $this->userService->store($data);

         return (new UserTransformer())->transform($user);
    }

    /**
     * @param $request
     * @throws ValidationException
     */
    private function validation($request)
    {
        $validator = \Validator::make($request->all(), [
            self::EMAIL => 'required|email|unique:users',
            self::NAME => 'required'
        ]);
        if ($validator->fails()) {
            $this->errorBadRequest($validator);
        }
    }
}
