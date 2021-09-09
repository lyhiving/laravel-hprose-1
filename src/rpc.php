<?php
use whereof\hprose\Facades\HproseRoute;
// 注册callback
HproseRoute::add(function () {
    return 'service hello';
}, 'hello');
// 注册class
HproseRoute::add(\whereof\hprose\Services\UserServer::class);
//注册中间价
HproseRoute::addInvokeHandler(function ($name, array &$args, stdClass $context, Closure $next) {
    \whereof\hprose\Support\LaravelHelper::log('调用的远程函数/方法名:' . $name, 'debug', $args);
    $result = $next($name, $args, $context);
    return $result;
});
