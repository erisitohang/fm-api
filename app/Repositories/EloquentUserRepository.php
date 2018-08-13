<?php

namespace App\Repositories;


use App\Repositories\Contracts\UserRepositoryInterface;

class EloquentUserRepository extends AbstractEloquentRepository implements UserRepositoryInterface
{
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

    public function custom()
    {
        return $this->model
            ->whereIn('id', $inIds)
            ->whereNotIn('id', $notInIds)
            ->get();
    }
}
