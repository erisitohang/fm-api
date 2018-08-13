<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Responses\Api\Feed\IndexResponse;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    /**
     * @var IndexResponse
     */
    private $indexResponse;

    public function __construct(IndexResponse $indexResponse)
    {
        $this->indexResponse = $indexResponse;
    }

    /**
     * @param Request $request
     * @return IndexResponse
     */
    public function index(Request $request)
    {
        return $this->indexResponse;
    }
}