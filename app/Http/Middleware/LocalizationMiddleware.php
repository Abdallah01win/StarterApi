<?php

namespace App\Http\Middleware;

use App\Enums\Locals;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocalizationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->getPreferredLanguage(Locals::getValues());

        if ($locale) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
