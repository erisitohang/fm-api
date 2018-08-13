<?php

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function createUsers($amount = 2)
    {
        $users = [];
        for($i=0; $i < $amount; $i++) {
            $index = $i+1;
            $response = $this->json('POST', '/user', [
                'email'     => "test{$index}@test.com",
                'name' => "John Doe{$index}"
            ])->response;

            $users[] = json_decode($response->getContent(), true);
        }

        return $users;
    }
}
