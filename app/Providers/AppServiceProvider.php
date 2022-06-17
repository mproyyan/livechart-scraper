<?php

namespace App\Providers;

use App\Contracts\AnimeDetailInterface;
use App\Contracts\AnimeMovieInterface;
use App\Contracts\AnimeOvaInterface;
use App\Contracts\AnimeTvInterface;
use App\Contracts\UserInterface;
use App\Models\AnimeDetail;
use App\Models\AnimeMovie;
use App\Models\AnimeOva;
use App\Models\AnimeTv;
use Illuminate\Support\ServiceProvider;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public array $bindings = [
        UserInterface::class => User::class,
        AnimeTvInterface::class => AnimeTv::class,
        AnimeMovieInterface::class => AnimeMovie::class,
        AnimeOvaInterface::class => AnimeOva::class,
        AnimeDetailInterface::class => AnimeDetail::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
