<?php

namespace App\Repositories\Contracts;


interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function inAndNotIn($inIds, $notInIds);
}
