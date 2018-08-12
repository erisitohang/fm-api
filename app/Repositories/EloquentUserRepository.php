<?php

namespace App\Repositories;


use App\Repositories\Contracts\UserRepositoryInterface;

class EloquentUserRepository extends AbstractEloquentRepository implements UserRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function save(array $data)
    {
        $user = parent::save($data);

        return $user;
    }
}
