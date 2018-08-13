<?php

namespace Tests\Endpoints;


use Laravel\Lumen\Testing\DatabaseMigrations;

class SubscriberTest extends \TestCase
{
    use DatabaseMigrations;

    public function testSubscribe()
    {
        list($user1, $user2) = $this->createUsers();
        $data = [
            'requestor' =>  $user1['email'],
            'target' =>  $user2['email']
        ];

        $response = $this->json('POST', '/subscribe', $data);
        $response
            ->seeJsonEquals([
                'success' => true,
            ]);
    }

    public function testBlock()
    {
        list($user1, $user2) = $this->createUsers();
        $data = [
            'requestor' =>  $user1['email'],
            'target' =>  $user2['email']
        ];

        $response = $this->json('POST', '/subscribe/block', $data);
        $response
            ->seeJsonEquals([
                'success' => true,
            ]);
    }
}
