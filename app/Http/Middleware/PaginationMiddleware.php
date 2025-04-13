<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaginationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->method() === 'GET' && $response->getStatusCode() === 200) {

            $data = $response->getData(true);

            $keys = ['links', 'first_page_url', 'last_page_url', 'next_page_url', 'path', 'prev_page_url', 'last_page', 'from'];

            foreach ($keys as $key) {
                if (array_key_exists($key, $data)) {
                    unset($data[$key]);
                }

                if (isset($data['meta']) && array_key_exists($key, $data['meta'])) {
                    unset($data['meta'][$key]);
                }
            }

            $response->setData($data);

            return $response;
        }

        return $response;
    }
}
