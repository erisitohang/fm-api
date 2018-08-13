<?php

namespace Tests\Endpoints;


use Laravel\Lumen\Testing\DatabaseMigrations;

class RelationshipTest extends \TestCase
{
    use DatabaseMigrations;

    public function testCreatingRelationship()
    {
        list($user1, $user2) = $this->createUsers(2);
        $data["friends"] = [
            $user1['email'],
            $user2['email']
        ];

        $response = $this->json('POST', '/friend', $data);
        $response
            ->seeJsonEquals([
                'success' => true,
            ]);
    }

    public function testFriendList()
    {
        list($user1) = $this->createUsers(1);
        $data["email"] = $user1['email'];

        $response = $this->json('POST', '/friend/mine', $data);
        $response
            ->seeJson([
                'success' => true,
            ]);
    }

    public function testCommonFriend()
    {
        list($user1, $user2, $user3) = $this->createUsers(3);
        $data["friends"] = [
            $user1['email'],
            $user3['email']
        ];
        $this->json('POST', '/friend', $data);

        $data["friends"] = [
            $user2['email'],
            $user3['email']
        ];
        $this->json('POST', '/friend', $data);

        $data["friends"] = [
            $user1['email'],
            $user2['email']
        ];
        $response = $this->json('POST', '/friend/common', $data);
        $response
            ->seeJson([
                'success' => true,
                'friends' => [
                    $user3['email']
                ]
            ]);
    }
}
