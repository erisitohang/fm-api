<?php
namespace Tests\Endpoints;
use Laravel\Lumen\Testing\DatabaseMigrations;

class UsersTest extends \TestCase
{
    use DatabaseMigrations;

    public function testCreatingUser()
    {
        // no data
        $this->call('POST', '/user', []);
        $this->assertResponseStatus(422);

        // should work now
        $this->json('POST', '/user', [
            'email'     => 'test@test.com',
            'name' => 'John Doe',
        ]);

        $this->assertResponseStatus(200);

        // same email should give 422 invalid
        $this->call('POST', '/user', [
            'email'     => 'test@test.com',
            'name' => 'Mike'
        ]);

        $this->assertResponseStatus(422);
    }
}
