<?php

namespace App\Providers;

use App\Enums\RoleNames;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        Gate::after(function ($user) {
            if ($user->hasRole(RoleNames::SUPER_ADMIN)) {
                return true;
            }
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip())->response(function () {
                return response()->json(['message' => __('auth.throttle')], 429);
            });
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())->response(function () {
                return response()->json(['message' => __('auth.throttle')], 429);
            });
        });
    }
}
