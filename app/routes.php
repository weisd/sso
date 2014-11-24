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

Route::get('/register', "SSOController@register");
Route::post('/register', ['before' => 'csrf', 'uses' => "SSOController@registerPost"]);

// 登陆
Route::get('/login', "SSOController@login");
Route::post('/login', ['before' => 'csrf', 'uses' => "SSOController@loginPost"]);

// 同步登陆接口
Route::get('/sync', "SSOController@sync");
Route::get('/dologin', "SSOController@doLogin");
// 退出
Route::get('/logout', "SSOController@logout");

Route::get('/', function () {
	return 'sso system';
});

App::missing(function ($exception) {
	return 'page not found';
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