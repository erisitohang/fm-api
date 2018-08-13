<?php

namespace App\Providers;

use App\Models\Relationship;
use App\Models\User;
use App\Repositories\Contracts\RelationshipRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\EloquentRelationshipRepository;
use App\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, function () {
            return new EloquentUserRepository(new User());
        });

        $this->app->bind(RelationshipRepositoryInterface::class, function () {
            return new EloquentRelationshipRepository(new Relationship());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            UserRepositoryInterface::class,
            RelationshipRepositoryInterface::class
        ];
    }
}
