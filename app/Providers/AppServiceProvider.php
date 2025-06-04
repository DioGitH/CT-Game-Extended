<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ChallengeResult;
use App\Observers\ChallengeResultObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ChallengeResult::observe(ChallengeResultObserver::class);
    }
}
