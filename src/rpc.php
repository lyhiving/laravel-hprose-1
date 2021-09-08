<?php


\whereof\hprose\Facades\HproseRoute::add(function () {
    return 'service hello';
}, 'hello');

\whereof\hprose\Facades\HproseRoute::add(function () {
    return 'LogFilter';
}, 'myfilter')->addFilter(new \whereof\hprose\Filter\LogFilter());

\whereof\hprose\Facades\HproseRoute::add(function ($a, $b) {
    return 'InvokeHandler 参数：'.$a.'.'.$b;
}, 'myhandler')->addInvokeHandler(function ($name, array &$args, stdClass $context, Closure $next) {
    \whereof\hprose\Support\LaravelHelper::log('调用的远程函数/方法名:' . $name, 'debug', $args);
    $result = $next($name, $args, $context);
    return $result;
});

\whereof\hprose\Facades\HproseRoute::add(\whereof\hprose\Services\UserServer::class);
