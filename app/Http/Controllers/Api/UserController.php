<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\User\StoreResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var StoreResponse
     */
    private $storeResponse;

    public function __construct(StoreResponse $storeResponse)
    {
        $this->storeResponse = $storeResponse;
    }

    public function store(Request $request)
    {
        return $this->storeResponse;
    }

}
