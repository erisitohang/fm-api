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

    /**
     * @param string $emails
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findEmail($emails)
    {
        return $this->userRepository->findIn('email', $emails);
    }

    /**
     * @param string $email
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findByEmail($email)
    {
        return $this->userRepository->findOneBy([
            'email' => $email
        ]);
    }

    /**
     * @param array $inIds
     * @param array $notInIds
     * @return mixed
     */
    public function inAndNotIn($inIds, $notInIds)
    {
        return $this->userRepository->inAndNotIn($inIds, $notInIds);
    }
}
