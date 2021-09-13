<?php

namespace whereof\hprose\Commands;

use whereof\hprose\Support\LaravelHelper;

/**
 * Class SocketCommand
 * @package whereof\hprose\Commands
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
    protected $description = 'hprose rpc socket 服务 [start|stop|status|daemon]';

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
        }
    }

    public function start()
    {
        $this->printLog();
        app('hprose.socket.server')->start();
    }

    public function stop()
    {
        if (file_exists(LaravelHelper::pidfile())) {
            $pid = file_get_contents(LaravelHelper::pidfile());
            posix_kill($pid, SIGTERM);
            unlink(LaravelHelper::pidfile());
            $this->line(sprintf('%s stop...', $this->arguments()['command']));
            return;
        }
        $this->line(sprintf('%s not running', $this->arguments()['command']));
    }

    public function status()
    {
        if (file_exists(LaravelHelper::pidfile())) {
            $pid = file_get_contents(LaravelHelper::pidfile());
            $this->line(sprintf('%s running, pid = %s', $this->arguments()['command'], $pid));
        } else {
            $this->line(sprintf('%s not running', $this->arguments()['command']));
        }
    }

    public function daemon()
    {
        if (file_exists(LaravelHelper::pidfile())) {
            $this->line(sprintf('%s already running', $this->arguments()['command']));
            exit(0);
        }
        $this->line(sprintf('%s start...', $this->arguments()['command']));
        $pid = pcntl_fork();
        if ($pid == -1) {
            die('could not fork');
        } else if ($pid) {
            //pcntl_wait($status); //等待子进程中断，防止子进程成为僵尸进程。
            exit(0);
        } else {
            posix_setsid();
            file_put_contents(LaravelHelper::pidfile(), posix_getpid());
            $this->start();
        }
    }

}
