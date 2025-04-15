<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransformJsonResponse
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $response->setData($this->transformKeys($response->getData(true)));
        }

        return $response;
    }

    /**
     * Transform array keys from snake_case to camelCase recursively.
     *
     * @param  array<int, mixed>|object|string|int|bool|null  $data
     * @return mixed
     */
    protected function transformKeys($data)
    {
        if (! is_array($data) && ! is_object($data)) {
            return $data;
        }

        $array       = is_array($data) ? $data : (array) $data;
        $transformed = [];

        foreach ($array as $key => $value) {
            $transformedKey               = is_string($key) ? $this->snakeToCamel($key) : $key;
            $transformed[$transformedKey] = $this->transformKeys($value);
        }

        return is_object($data) ? (object) $transformed : $transformed;
    }

    /**
     * Convert a string from snake_case to camelCase.
     */
    protected function snakeToCamel(string $string): string
    {
        return lcfirst(str_replace('_', '', ucwords($string, '_')));
    }
}
