<?php

/*
|--------------------------------------------------------------------------
| Application Routes
| 应用程序路由
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| 在这里你可以为应用程序注册所有的路由。
| It's a breeze. Simply tell Laravel the URIs it should respond to
| 这非常的容易。简单的告诉 Laravel 这些 URI 应该响应，
| and give it the Closure to execute when that URI is requested.
| 并且给它一个闭包，当那个URI发起请求后执行它。
|
 */

Route::post('/comet/server', ['before' => 'kt_token', 'uses' => "CometController@servers"]);
Route::get('/comet/server/test', ['before' => 'kt_token', 'uses' => "CometController@servers"]);

Route::post('/message/push', ['before' => 'kt_token', 'uses' => 'MessageController@push']);
Route::get('/message/push/test', ['before' => 'kt_token', 'uses' => 'MessageController@push']);

Route::get('/message/history', ['before' => 'kt_token', 'uses' => 'MessageController@history']);

Route::get('/test', function () {
	Queue::push('MessageWorker', ['name' => 'dada']);
	return 'test';
});

// // 博客
// Route::controller('blog', 'BlogController');

// // 基本 要放在其他route下面
// Route::controller('/', 'SiteController', [
// 	'getIndex' => 'index',
// 	'getActivate' => 'activate',
// 	'getSignup' => 'signup',
// 	'getSignin' => 'signin',
// ]);

// // 所有post请求csrf验证
// Route::when('*', 'csrf', ['post']);