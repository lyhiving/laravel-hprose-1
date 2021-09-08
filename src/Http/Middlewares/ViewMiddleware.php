<?php

namespace whereof\hprose\Http\Middlewares;

use Closure;


/**
 * Class ViewMiddleware
 * @package whereof\hprose\Http\Middlewares
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