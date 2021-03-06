
> 基于 [hprose/hprose-php](https://github.com/hprose/hprose-php) 开发的Laravel扩展：[whereof/laravel-hprose](https://github.com/whereof/laravel-hprose)

[![Latest Stable Version](http://poser.pugx.org/whereof/laravel-hprose/v)](https://packagist.org/packages/whereof/laravel-hprose) [![Total Downloads](http://poser.pugx.org/whereof/laravel-hprose/downloads)](https://packagist.org/packages/whereof/laravel-hprose) [![Latest Unstable Version](http://poser.pugx.org/whereof/laravel-hprose/v/unstable)](https://packagist.org/packages/whereof/laravel-hprose) [![License](http://poser.pugx.org/whereof/laravel-hprose/license)](https://packagist.org/packages/whereof/laravel-hprose) [![PHP Version Require](http://poser.pugx.org/whereof/laravel-hprose/require/php)](https://packagist.org/packages/whereof/laravel-hprose)


## 安装
~~~
composer require whereof/laravel-hprose
~~~

## 配置文件
~~~
<?php

return [
    //rpc 服务
    'server' => [
        // hprose 调试模式
        'debug' => true,
        //监听地址
        'tcp_uris'       => [
            'tcp://0.0.0.0:1314',
        ],
        //注册rpc 服务 目录地址
        'route_path'     => glob(base_path("rpc") . '/*.php'),
        // 通过路由查看注册的方法
        'http'           => [
            // 如果设置false 在控制台显示调用方法，否在在路由显示调用方法
            'enable'       => false,
            //如果设置了true 这里就是路由前缀
            'route_prefix' => 'rpc'
        ],
    ],
    //rpc 客户端
    'client' => [
        // 服务端监听地址
        'tcp_uris' => [
            'tcp://127.0.0.1:1314',
        ],
        //是否异步
        'async'    => false
    ],
];
~~~

## 日志记录 `/config/logging.php`
~~~
'channels' => [
     ............
     'hprose' => [
      	'driver' => 'daily',
      	'path'   => storage_path('logs/hprose.log'),
      	'days'   => 14,
      ],
]
~~~
> 非强制配置，不配置，就会走默认的日志记录

## laravel配置

~~~
//在 `config/app.php` 注册 HproseServiceProvider 
'providers' => [
    .....
    \whereof\laravel\hprose\HproseServiceProvider::class
]
php artisan vendor:publish --provider="whereof\laravel\hprose\HproseServiceProvider"
~~~

## Lumen配置

~~~
将配置信息放在/config/hprose.php

/bootstrap/app.php
$app->register(\whereof\laravel\hprose\HproseServiceProvider::class);

/路由注册 rpc/demo.php
<?php
use whereof\laravel\hprose\Facades\HproseRoute;
// 注册callback
HproseRoute::add(function () {
    return 'service hello';
}, 'hello');
// 注册class
HproseRoute::add(\whereof\laravel\hprose\Services\UserServer::class);

//注册中间价
HproseRoute::addInvokeHandler(function ($name, array &$args, stdClass $context, Closure $next) {
    \whereof\laravel\hprose\Support\LaravelHelper::log('调用的远程函数/方法名:' . $name, 'debug', $args);
    $result = $next($name, $args, $context);
    return $result;
});
~~~

## 服务端 方法注入，类注入以及目录下类注入

~~~
<?php

use whereof\laravel\hprose\Facades\HproseRoute;
// 注册callback
HproseRoute::add(function () {
    return 'service hello';
}, 'hello');
// 注册class
HproseRoute::add(\whereof\laravel\hprose\Services\UserServer::class);

//注册中间价
HproseRoute::addInvokeHandler(function ($name, array &$args, stdClass $context, Closure $next) {
    \whereof\laravel\hprose\Support\LaravelHelper::log('调用的远程函数/方法名:' . $name, 'debug', $args);
    $result = $next($name, $args, $context);
    return $result;
});
// 注册整个目录
HproseRoute::addPath(app_path('Services'));
~~~

>   使用addPath的时候要注意：在类中构造方法__construct 参数不能是必传参数.
>

## 启动rpc服务

~~~
php artisan hprose:socket
~~~

## 客户端调用

~~~
$uris =['tcp://127.0.0.1:1314'];
$client = new \whereof\laravel\hprose\Clients\SocketClient($uris, false);
$client->hello()
$client->whereof_hprose_demoService->kan()

需要配置配置
'client' => [
  'tcp_uris' => [
  	'tcp://127.0.0.1:1314',
  ],
	'async' => false
],
app('hprose.socket.client')->hello()
~~~


##  加入我们

如果你认可我们的开源项目，有兴趣为 laravel-hprose 的发展做贡献，竭诚欢迎加入我们一起开发完善。无论是[报告错误](https://github.com/whereof/laravel-hprose/issues)或是 Pull Request 开发，那怕是修改一个错别字也是对我们莫大的帮助。


##  关于我
https://github.com/whereof/whereof

##  许可协议
[MIT](https://opensource.org/licenses/MIT)
