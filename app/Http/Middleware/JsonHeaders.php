<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Http\Request;

class JsonHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        $ct = $response->headers->get('Content-type');
        if ($ct !== 'image/jpeg') {
            $response->header('Content-type', 'application/json');
        }


        return $response;
    }
}
