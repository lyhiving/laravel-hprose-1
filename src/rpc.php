<?php


use whereof\hprose\Services\UserServer;

\whereof\hprose\Facades\HproseRoute::add(function () {
    return 'service hello';
}, 'hello');

\whereof\hprose\Facades\HproseRoute::add(UserServer::class);
