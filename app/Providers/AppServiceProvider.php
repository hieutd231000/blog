<?php

namespace App\Providers;

use App\Repositories\Comments\CommentEloquentRepository;
use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\News\NewEloquentRepository;
use App\Repositories\News\NewRepositoryInterface;
use App\Repositories\Reactions\ReactionEloquentRepository;
use App\Repositories\Reactions\ReactionRepositoryInterface;
use App\Repositories\User_Reactions\UserReactionEloquentRepository;
use App\Repositories\User_Reactions\UserReactionRepositoryInterface;
use App\Repositories\Users\UserEloquentRepository;
use App\Repositories\Users\UserRepositoryInterface;
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
        $this->app->bind(
            NewRepositoryInterface::class,
            NewEloquentRepository::class,
        );
        $this->app->bind(
            ReactionRepositoryInterface::class,
            ReactionEloquentRepository::class,
        );
        $this->app->bind(
            CommentRepositoryInterface::class,
            CommentEloquentRepository::class,
        );
        $this->app->bind(
            UserReactionRepositoryInterface::class,
            UserReactionEloquentRepository::class,
        );
        $this->app->bind(
            UserRepositoryInterface::class,
            UserEloquentRepository::class,
        );
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
