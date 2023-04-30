<?php

namespace App\Providers;

use App\Repositories\FriendRepository\FriendRepository;
use App\Repositories\FriendRepository\FriendRepositoryInterface;
use App\Repositories\UserRepository\UserRepository;
use App\Repositories\UserRepository\UserRepositoryInterface;
use App\Services\Friend\FriendService;
use App\Services\Friend\FriendServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FriendServiceInterface::class, FriendService::class);
        $this->app->bind(FriendRepositoryInterface::class, FriendRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
