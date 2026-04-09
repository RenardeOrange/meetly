<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetLocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $langue = Auth::user()->langue ?? 'fr';
            App::setLocale($langue);
        }

        return $next($request);
    }
}
