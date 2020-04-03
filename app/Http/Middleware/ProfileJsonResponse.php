<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class ProfileJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!app()->bound('debugbar') || !app('debugbar')->isEnabled()) {
            return $response;
        }

        if ($response instanceof JsonResponse && $request->has('_debug')) {
            $response->setData(array_merge([
                '_debugbar' => Arr::only(app('debugbar')->getData(), 'queries'),
            ], $response->getData(true)));
        }
        return $response;
    }
}
