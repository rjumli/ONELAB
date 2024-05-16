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
        $currentUrl = $_SERVER['REQUEST_URI'];
        $route = parse_url($currentUrl, PHP_URL_PATH);
        if($route != '/'){
            $isActive = ListMenu::where('route', $route)->value('is_active');
            if (!$isActive) {
                abort(403, 'Try something new?');
            }
        }
        return $next($request);
    }
}
