<?php

namespace Tests\Endpoints;


use Laravel\Lumen\Testing\DatabaseMigrations;

class FeedTest extends \TestCase
{
    use DatabaseMigrations;

    public function testPostFeed()
    {
        /**
         * $user1 sender
         * $user2 friend
         * $user3 friend & block=1
         * $user4 subscriber=1 & block=0
         * $user5 subscriber=0 & block=1
         * $user6 mention
         * $user7 mention  & block=  1
         * Expected : $user2, $user4, $user6
         *
         */

        list($user1, $user2, $user3, $user4, $user5, $user6, $user7) = $this->createUsers(7);

        //friend
        $data["friends"] = [
            $user2['email'],
            $user1['email']
        ];
        $this->json('POST', '/friend', $data);

        //friend & block
        $data["friends"] = [
            $user1['email'],
            $user3['email']
        ];
        $this->json('POST', '/friend', $data);
        $data = [
            'requestor' =>  $user3['email'],
            'target' =>  $user1['email']
        ];
        $this->json('POST', '/subscribe/block', $data);

        // subscriber=1 & block=0
        $data = [
            'requestor' =>  $user4['email'],
            'target' =>  $user1['email']
        ];
        $this->json('POST', '/subscribe', $data);

        // subscriber=0 & block=1
        $data = [
            'requestor' =>  $user5['email'],
            'target' =>  $user1['email']
        ];
        $this->json('POST', '/subscribe/block', $data);

        // subscriber=0 & block=1
        $data = [
            'requestor' =>  $user7['email'],
            'target' =>  $user1['email']
        ];
        $this->json('POST', '/subscribe/block', $data);


        $text = sprintf(
            'Hello %s & %s',
            $user6['email'],
            $user7['email']
        );
        $data = [
            'sender' =>  $user1['email'],
            'text' =>  $text
        ];

        $response = $this->json('POST', '/feed', $data);
        $response
            ->seeJsonEquals([
                'success' => true,
                'recipients' => [
                    $user2['email'],
                    $user4['email'],
                    $user6['email']
                ]
            ]);
    }
}
