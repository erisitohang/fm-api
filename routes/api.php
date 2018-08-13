<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return [
        "success" =>  true,
        "message" => $router->app->version()
    ];
});

$router->post('/user', [
    'as' => 'user', 'uses' => 'Api\UserController@store'
]);

$router->post('/friend', [
    'as' => 'friend', 'uses' => 'Api\RelationshipController@store'
]);
$router->post('/friend/mine', [
    'as' => 'friend.mine', 'uses' => 'Api\RelationshipController@mine'
]);
$router->post('/friend/common', [
    'as' => 'friend.common', 'uses' => 'Api\RelationshipController@common'
]);

$router->post('/subscribe', [
    'as' => 'subscribe.store', 'uses' => 'Api\SubscriberController@store'
]);
$router->post('/subscribe/block', [
    'as' => 'subscribe.block', 'uses' => 'Api\SubscriberController@block'
]);

$router->post('/feed', [
    'as' => 'feed.index', 'uses' => 'Api\FeedController@index'
]);