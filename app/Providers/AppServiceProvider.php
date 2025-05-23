<?php

namespace App\Providers;

use App\Enums\ResponseCode;
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
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip())->response(function () {
                return response()->json(['message' => __('auth.throttle.api')], ResponseCode::TOO_MANY_REQUESTS);
            });
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())->response(function () {
                return response()->json(['message' => __('auth.throttle.login')], ResponseCode::TOO_MANY_REQUESTS);
            });
        });
    }
}
