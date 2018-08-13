<?php

namespace App\Repositories;


use App\Repositories\Contracts\RelationshipRepositoryInterface;
use App\Services\RelationshipService;

class EloquentRelationshipRepository extends AbstractEloquentRepository implements RelationshipRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function findUserId($userId)
    {
        return $this->model
            ->where(function ($query) use ($userId) {
                $query->where(RelationshipService::USER_ONE_ID, $userId)
                    ->orWhere(RelationshipService::USER_TWO_ID, $userId);
            })
            ->get();
    }
}
