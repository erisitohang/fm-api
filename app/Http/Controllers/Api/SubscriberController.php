<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\Subscriber\StoreResponse;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    /**
     * @var StoreResponse
     */
    private $storeResponse;

    /**
     * SubscriberController constructor.
     * @param StoreResponse $storeResponse
     */
    public function __construct(StoreResponse $storeResponse)
    {
        $this->storeResponse = $storeResponse;
    }

    /**
     * @param Request $request
     * @return StoreResponse
     */
    public function store(Request $request)
    {
        return $this->storeResponse;
    }

    /**
     * @param Request $request
     * @return StoreResponse
     */
    public function block(Request $request)
    {
        $this->storeResponse->isBlock(true);
        return $this->storeResponse;
    }
}
