<?php

namespace App\Providers;

use App\Repositories\Contracts\TrashInterface;
use App\Repositories\Contracts\UserInterface;
use App\Repositories\TrashRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider{
    public function register(): void
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(TrashInterface::class, TrashRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

    }
}
