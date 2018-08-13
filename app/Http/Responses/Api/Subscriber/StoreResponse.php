<?php

namespace App\Http\Responses\Api\Subscriber;


use App\Exceptions\ValidationHttpException;
use App\Http\Responses\BaseResponse;
use App\Services\SubscriberService;
use App\Services\UserService;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Collection;

class StoreResponse extends BaseResponse implements Responsable
{

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var SubscriberService
     */
    private $subscriberService;

    /**
     * @var Collection
     */
    private $users;

    /**
     * @var bool
     */
    private $block = false;

    public function __construct(UserService $userService, SubscriberService $subscriberService)
    {
        $this->userService = $userService;
        $this->subscriberService = $subscriberService;
    }

    public function isBlock($value)
    {
        $this->block = $value;
    }

    public function toResponse($request)
    {
        $request = $this->getRequest();
        $this->validation($request);
        $requestor = $request->get('requestor');
        $userId = 0;
        $targetId = 0;
        foreach ($this->users as $user) {
            if ($requestor === $user->email) {
                $userId = $user->id;
            } else {
                $targetId = $user->id;
            }
        }

        if ($this->block) {
            $model =  $this->subscriberService->block($userId, $targetId);
        } else {
            $model =  $this->subscriberService->subscribe($userId, $targetId);
        }

        if ($model) {
            return ['success' => true];
        }

        return ['failed' => true];
    }

    private function validation($request)
    {
        $validator = \Validator::make($request->all(), [
            'requestor' => 'required|email',
            'target' => 'required|email',
        ]);
        if ($validator->fails()) {
            $this->errorBadRequest($validator);
        }

        $requestor = $request->get('requestor');
        $target = $request->get('target');
        if (!$requestor || !$target || $requestor === $target) {
            throw new ValidationHttpException([self::EMAILS_ARE_REQUIRED]);
        }

        $emails = [$requestor, $target];
        $this->users = $this->userService->findEmail($emails);
        if (count($this->users) !== self::NUMBER_OF_EMAILS_REQUIRED) {
            throw new ValidationHttpException([self::EMAILS_ARE_REQUIRED]);
        }
    }
}