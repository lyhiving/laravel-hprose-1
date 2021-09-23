<?php

namespace whereof\laravel\hprose\Http\Controller;

use Illuminate\Routing\Controller;
use whereof\laravel\hprose\Facades\HproseRoute;

/**
 * Class IndexController
 * @package whereof\laravel\hprose\Http\Controller
 */
class IndexController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $list = HproseRoute::getClassMethodArgs();
        return view('hprose::index', ['list' => $list]);
    }
}