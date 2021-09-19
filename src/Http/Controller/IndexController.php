<?php

namespace whereof\hprose\Http\Controller;

use Illuminate\Routing\Controller;
use whereof\hprose\Facades\HproseRoute;

/**
 * Class IndexController
 * @package whereof\hprose\Http\Controller
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