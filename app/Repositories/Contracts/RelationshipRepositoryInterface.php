<?php

namespace App\Repositories\Contracts;


interface RelationshipRepositoryInterface extends BaseRepositoryInterface
{
    public function findUserId($userId);
}
