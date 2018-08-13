<?php

namespace App\Models;


use App\Services\RelationshipService;

class Relationship extends BaseModel
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->hidden = array_merge($this->hidden, ['id']);
    }

    protected $fillable = [
        'user_one_id',
        'user_two_id'
    ];

    public function userOne()
    {
        return $this->hasOne('App\Models\User', 'id', RelationshipService::USER_ONE_ID);
    }

    public function userTwo()
    {
        return $this->hasOne('App\Models\User', 'id', RelationshipService::USER_TWO_ID);
    }
}
