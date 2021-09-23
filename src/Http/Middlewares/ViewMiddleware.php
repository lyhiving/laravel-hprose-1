<?php

namespace whereof\laravel\hprose\Http\Middlewares;

use Closure;


/**
 * Route::prefix(config('hprose.server.http.route_prefix', 'rpc'))
 * ->middleware(["whereof\laravel\hprose\Http\Middlewares\ViewMiddleware::class"])
 * ->namespace('whereof\laravel\hprose\Http\Controller')
 * ->group(function (\Illuminate\Routing\Router $router) {
 * $router->get('/', [\whereof\laravel\hprose\Http\Controller\IndexController::class, 'index']);
 * });
 * Class ViewMiddleware
 * @package whereof\laravel\hprose\Http\Middlewares
 */
class ViewMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $view = app('view')->getFinder();
        $view->prependLocation(__DIR__ . '/../Views/');
        return $next($request);
    }
}