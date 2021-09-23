<?php


namespace whereof\laravel\hprose\Commands;

use whereof\laravel\hprose\Facades\HproseRoute;
use Illuminate\Console\Command;
use whereof\laravel\hprose\Support\LaravelHelper;


/**
 * Class BaseCommand
 * @package whereof\laravel\hprose\Commands
 */
class BaseCommand extends Command
{

    /**
     * 打印日志
     */
    public function printLog()
    {
        $this->output->writeln(LaravelHelper::version());
        $this->output->newLine();
        $uris = config('hprose.server.tcp_uris');
        foreach ($uris as $uri) {
            $this->line(sprintf(' - <info>%s</>', $uri));
        }
        $this->comment('可调用远程方法:');
        if (config('hprose.server.http.enable', false) && app() instanceof \Illuminate\Foundation\Application) {
            $this->line('前往http路由进行查看：' . '/' . config('hprose.server.http.route_prefix', 'rpc'));
            return;
        }
        $this->output->newLine();
        $classMethodArgs = HproseRoute::getClassMethodArgs();
        if ($classMethodArgs) {
            foreach ($classMethodArgs as $arg) {
                if (empty($arg['class'])) {
                    $method = $arg['alias'] . '(' . implode(',', $arg['args']) . ')';
                } else {
                    $method = $arg['alias'] . '->' . $arg['method'] . '(' . implode(',', $arg['args']) . ')';
                }
                $this->line(sprintf(' - <info>%s</>', $method));
            }
            $this->output->newLine();
        } else {
            $this->line(sprintf(' - <info>无可调用方法</>'));
        }
    }
}