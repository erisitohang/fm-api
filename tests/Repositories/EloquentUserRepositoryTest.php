<?php

namespace Tests\Repositories;

use App\Models\User;
use App\Repositories\EloquentUserRepository;
use Laravel\Lumen\Testing\DatabaseMigrations;

class EloquentUserRepositoryTest extends \TestCase
{
    use DatabaseMigrations;
    /**
     * @var EloquentUserRepository
     */
    protected $eloquentUserRepository;

    public function setup()
    {
        parent::setUp();
        $this->eloquentUserRepository = new EloquentUserRepository(new User());
    }

    public function testCreateUser()
    {
        $testUserArray = factory(User::class)->make()->toArray();
        $user = $this->eloquentUserRepository->save($testUserArray);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($testUserArray['email'], $user->email);
    }
}