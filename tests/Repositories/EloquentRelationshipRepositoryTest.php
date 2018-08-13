<?php

namespace Tests\Repositories;

use App\Models\Relationship;
use App\Models\User;
use App\Repositories\EloquentRelationshipRepository;
use App\Repositories\EloquentUserRepository;
use Laravel\Lumen\Testing\DatabaseMigrations;

class EloquentRelationshipRepositoryTest extends \TestCase
{
    use DatabaseMigrations;
    /**
     * @var EloquentRelationshipRepository
     */
    protected $eloquentRelationshipRepository;

    /**
     * @var EloquentUserRepository
     */
    protected $eloquentUserRepository;

    public function setup()
    {
        parent::setUp();
        $this->eloquentRelationshipRepository = new EloquentRelationshipRepository(new Relationship());
        $this->eloquentUserRepository = new EloquentUserRepository(new User());
    }

    public function testCreateRelationship()
    {
        $testUserArray = factory(User::class)->make()->toArray();
        $user1 = $this->eloquentUserRepository->save($testUserArray);

        $testUserArray = factory(User::class)->make()->toArray();
        $user2 = $this->eloquentUserRepository->save($testUserArray);

        $data = [
            'user_one_id' => $user1->id,
            'user_two_id' => $user2->id
        ];

        $relationship = $this->eloquentRelationshipRepository->save($data);
        $this->assertInstanceOf(Relationship::class, $relationship);

        $this->assertEquals($user1->email, $relationship->userOne->email);
        $this->assertEquals($user2->email, $relationship->userTwo->email);
    }
}
