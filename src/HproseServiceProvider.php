<?php

namespace whereof\laravel\hprose;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use whereof\laravel\hprose\Clients\SocketClient;
use whereof\laravel\hprose\Commands\SocketCommand;
use whereof\laravel\hprose\Routing\Router;
use whereof\laravel\hprose\Servers\SocketServer;
use whereof\laravel\hprose\Support\{PidManager, LaravelHelper};

/**
 * Class HproseServiceProvider
 * @package whereof\laravel\hprose
 */
class HproseServiceProvider extends ServiceProvider
{

    /**
     *
     */
    public function boot()
    {
        $configsource = realpath(__DIR__ . '/config.php');
        $frame        = LaravelHelper::version(true);
        if ($frame == '') {
            return;
        }
        //laravel
        if ($frame == 'laravel') {
            $this->publishes([$configsource => config_path('hprose.php')]);
            $route = realpath(__DIR__ . '/rpc.php');
            $this->publishes([$route => base_path('rpc/demo.php')]);
            // http 查看注册方法 (command运行状态下)
            if (PidManager::getInstance(LaravelHelper::pidfile())->getPid() && config('hprose.server.http.enable', false)) {
                $this->registerRoutes();
            }
        }
        //Lumen
        if ($frame == 'lumen') {
            $this->app->configure('hprose');
        }
        $this->mergeConfigFrom($configsource, 'hprose');
        // 守护进程sh
        // $this->publishes([realpath(__DIR__ . '/daemon.sh') => base_path('hprose.sh')]);
        $this->loadRoute();

    }

    /**
     * 注册
     */
    public function register()
    {

        $this->registerRouter();
        $this->registerClient();
        $this->registerServer();
        $this->registerCommand();

    }


    /**
     * 注册命令行
     */
    protected function registerCommand()
    {
        $this->commands([
            SocketCommand::class
        ]);
    }

    /**
     * 注册rpc路由文件
     */
    protected function registerRouter()
    {
        $this->app->singleton('hprose.router', function ($app) {
            return new Router();
        });
    }

    /**
     * 注册客户端
     */
    protected function registerClient()
    {

        $this->app->singleton('hprose.socket.client', function ($app) {
            $uris            = config('hprose.client.tcp_uris');
            $async           = config('hprose.client.async', false);
            $client          = new SocketClient($uris, $async);
            $client->onError = function ($name, $error) {
                LaravelHelper::log($error);
            };
            return $client;
        });
    }

    /**
     * 注册socket服务
     */
    protected function registerServer()
    {

        $this->app->singleton('hprose.socket.server', function ($app) {
            $service              = new SocketServer();
            $service->onSendError = function ($error, \stdClass $context) {
                LaravelHelper::log($error);
            };
            $uris                 = config('hprose.server.tcp_uris');
            array_map(function ($url) use (&$service) {
                $service->addListener($url);
            }, $uris);
            //用来设置服务器是否是工作在 debug 模式下，
            //在该模式下，当服务器端发生异常时，将会将详细的错误堆栈信息返回给客户端，否则，只返回错误信息。
            $service->debug = config('hprose.server.debug', false);
            //null、数字（包括整数、浮点数）、Boolean 值、字符串、日期时间等基本类型的数据或者不包含引用的数组和对象。
            //当该属性设置为 true 时，在进行序列化操作时，将忽略引用处理，加快序列化速度.
            //将该属性设置为 true，可能会因为死循环导致堆栈溢出的错误。
            $service->simple = config('hprose.server.simple', false);
            //表示在调用执行时，如果发生异常，将延时一段时间后再返回给客户端。
            $service->errorDelay = config('hprose.server.errorDelay', 10000);
            return $service;
        });
    }


    /**
     * 加载注册路由文件
     */
    protected function loadRoute()
    {
        $files = config('hprose.server.route_path');
        if (is_array($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    require $file;
                }
            }
        }
        if (is_string($files) && is_file($files)) {
            require $files;
        }
    }

    /**
     * http 请求
     */
    public function registerRoutes()
    {
        $this->loadViewsFrom(__DIR__ . '/Http/Views/', 'hprose');
        Route::prefix(config('hprose.server.http.route_prefix', 'hprose'))
            ->group(function (\Illuminate\Routing\Router $router) {
                $router->get('/', [\whereof\laravel\hprose\Http\Controller\IndexController::class, 'index']);
            });
    }

}
