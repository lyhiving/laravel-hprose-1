<?php

namespace whereof\laravel\hprose\Commands;
/**
 * Class SocketCommand
 * @package whereof\laravel\hprose\Commands
 */
class SocketCommand extends BaseCommand
{
    /**
     * @var string
     */
    protected $signature = 'hprose:socket {cmd=start}';
    /**
     * @var string
     */
    protected $description = 'hprose rpc socket 服务 [start|stop|status|daemon|restart]';

    public function handle()
    {
        switch ($this->arguments()['cmd']) {
            case 'start':
                $this->start();
                break;
            case 'stop':
                $this->stop();
                break;
            case 'status':
                $this->status();
                break;
            case "daemon":
                $this->daemon();
                break;
            case "restart":
                $this->restart();
                break;
        }
    }

    public function start()
    {
        $this->printLog();
        app('hprose.socket.server')->start();
    }

    public function restart()
    {
        $this->stop();
        sleep(2);
        $this->daemon();
    }

    public function stop()
    {
        if ($pid = $this->pid()->getPid()) {
            $this->pid()->stop();
            $this->line(sprintf('%s stop, pid = %s', $this->arguments()['command'], $pid));
            return;
        }
        $this->line(sprintf('%s not running', $this->arguments()['command']));
    }

    public function status()
    {
        if ($pid = $this->pid()->getPid()) {
            $this->line(sprintf('%s running, pid = %s', $this->arguments()['command'], $pid));
        } else {
            $this->line(sprintf('%s not running', $this->arguments()['command']));
        }
    }

    public function daemon()
    {
        if ($pid = $this->pid()->getPid()) {
            $this->line(sprintf('%s already running，pid = %s', $this->arguments()['command'], $pid));
            exit(0);
        }
        $this->line(sprintf('%s start...', $this->arguments()['command']));

        $this->pid()->daemon(function () {
            $this->start();
        });
    }


}
