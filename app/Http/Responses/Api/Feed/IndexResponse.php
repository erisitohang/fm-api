<?php

namespace App\Http\Responses\Api\Feed;

use App\Exceptions\ValidationHttpException;
use App\Http\Responses\BaseResponse;
use App\Models\User;
use App\Services\FeedService;
use App\Services\RelationshipService;
use App\Services\SubscriberService;
use App\Services\UserService;
use Illuminate\Contracts\Support\Responsable;

class IndexResponse extends BaseResponse implements Responsable
{
    /**
     * @var FeedService
     */
    private $feedService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var RelationshipService
     */
    private $relationshipService;

    /**
     * @var SubscriberService
     */
    private $subscriberService;

    /**
     * @var User
     */
    private $user;

    public function __construct(
        FeedService $feedService,
        UserService $userService,
        RelationshipService $relationshipService,
        SubscriberService $subscriberService
    ) {
        $this->feedService =$feedService;
        $this->userService =$userService;
        $this->relationshipService =$relationshipService;
        $this->subscriberService =$subscriberService;
    }

    public function toResponse($request)
    {
        $request = $this->getRequest();
        $this->validation($request);

        $text = $request->get('text');
        $recipients = $this->feedService->recipients($this->user->id, $text);
        $emails = $recipients->pluck('email')->all();

        return [
            'success' => true,
            'recipients' => $emails
        ];
    }

    /**
     * @param $request
     */
    private function validation($request)
    {
        $validator = \Validator::make($request->all(), [
            'sender' => 'required|email',
            'text' => 'required'
        ]);

        if ($validator->fails()) {
            $this->errorBadRequest($validator);
        }
        $email = $request->get('sender');
        $this->user = $this->userService->findByEmail($email);
        if (!$this->user && !$this->user->id) {
            throw new ValidationHttpException([self::EMAILS_ARE_REQUIRED]);
        }
    }
}
