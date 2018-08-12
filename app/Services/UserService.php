<?php

namespace App\Services;


use App\Repositories\Contracts\UserRepositoryInterface;

class UserService
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function store(array $data)
    {
        return $this->userRepository->save($data);
    }
}
