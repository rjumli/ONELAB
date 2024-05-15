<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ListMenu;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuMiddleware
{
    public function handle(Request $request, Closure $next): Response
    { 
        $route = $request->route()->uri;
        if(!$route == '/'){
            $route = '/'.$request->route()->uri;
            $isActive = ListMenu::where('route', $route)->value('is_active');
            if (!$isActive) {
                abort(403, 'Try something new?');
            }
        }
        return $next($request);
    }
}
