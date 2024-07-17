<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {   
        // Post repository
        $this->app->bind(
            \App\Repositories\Interfaces\PostRepositoryInterface::class,
            \App\Repositories\Eloquent\PostRepository::class
        );

        // Add other repositories here
        // $this->app->bind(
        //     \App\Repositories\Interfaces\OtherModelRepositoryInterface::class,
        //     \App\Repositories\Eloquent\OtherModelRepository::class
        // );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
