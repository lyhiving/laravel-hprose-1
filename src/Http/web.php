<?php

use whereof\hprose\Http\Controller\IndexController;
use Illuminate\Support\Facades\Route;


Route::get('/', [IndexController::class, 'index']);
Route::any('/curl', [IndexController::class, 'rpcHttp']);

