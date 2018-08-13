<?php

namespace App\Transformers;


use App\Models\Relationship;
use App\Services\RelationshipService;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;

class RelationshipTransformer extends TransformerAbstract
{
    public function transform(Relationship $relationship)
    {
        $relationship[RelationshipService::USER_ONE_EMAIL] = $relationship->userOne->email;
        $relationship[RelationshipService::USER_TWO_EMAIL] = $relationship->userTwo->email;
        return $relationship->attributesToArray();
    }

    public function transformCollection($files)
    {
        $resource = new Collection($files, function(Relationship $model){
            return $this->transform($model);
        });

        $fractal = new Manager();

        $data =  $fractal->createData($resource)->toArray();

        return $data['data'];
    }
}
