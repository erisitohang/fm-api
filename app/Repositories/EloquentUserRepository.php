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

    /**
     * @param array $inIds
     * @param array $notInIds
     * @return mixed
     */
    public function inAndNotIn($inIds, $notInIds)
    {
        return $this->model
            ->whereIn('id', $inIds)
            ->whereNotIn('id', $notInIds)
            ->get();
    }
}
