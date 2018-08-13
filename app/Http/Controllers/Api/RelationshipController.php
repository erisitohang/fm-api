<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\Relationship\CommonResponse;
use App\Http\Responses\Api\Relationship\MineResponse;
use App\Http\Responses\Api\Relationship\StoreResponse;
use Illuminate\Http\Request;

class RelationshipController extends Controller
{
    /**
     * @var StoreResponse
     */
    private $storeResponse;

    /**
     * @var MineResponse
     */
    private $mineResponse;

    /**
     * @var CommonResponse
     */
    private $commonResponse;

    public function __construct(
        StoreResponse $storeResponse,
        MineResponse $mineResponse,
        CommonResponse $commonResponse
    ) {
        $this->storeResponse = $storeResponse;
        $this->mineResponse = $mineResponse;
        $this->commonResponse = $commonResponse;
    }

    public function store(Request $request)
    {
        return $this->storeResponse;
    }

    public function mine(Request $request)
    {
        return $this->mineResponse;
    }

    public function common(Request $request)
    {
        return $this->commonResponse;
    }
}
