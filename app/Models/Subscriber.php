<?php

namespace App\Models;


class Subscriber extends BaseModel
{
    protected $fillable = [
        'user_id',
        'target_id',
        'is_subscribe',
        'is_block'
    ];
}
