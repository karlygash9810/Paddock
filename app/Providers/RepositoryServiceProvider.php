<?php

namespace App\Providers;

use App\Repositories\PaddockRepository;
use App\Repositories\PaddockRespositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaddockRespositoryInterface::class, PaddockRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
