<?php

namespace App\Http\Responses\Api\User;

use App\Http\Responses\BaseResponse;
use App\Services\UserService;
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
            'email' => $request->get('email'),
            'name' => $request->get('name'),
        ];

        return $this->userService->store($data);

    }

    /**
     * @param $request
     * @throws ValidationException
     */
    private function validation($request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            $this->errorBadRequest($validator);
        }
    }
}
