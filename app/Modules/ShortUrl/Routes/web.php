<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('')->group(function() {
    // Auth - ok
    Auth::routes(['verify' => true]);
    // 首页 - ok
    Route::get('/', 'HomeController@dashboard')->name('home');
    // ok
    Route::get('privacy-policy', 'PagesController@privacy')->name('privacy');
    // ok
    Route::get('terms-of-use', 'PagesController@tos')->name('tos');


    Route::group(['middleware' => 'auth'], function () {
        // 编辑页 - ok
        Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
        // 编辑账户信息 - ok
        Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
        // 更改登录密码 - ok
        Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

        // 这种可生成对外的授权token，调用内部接口
        Route::get('profile/access-token', ['as' => 'access_token.index', 'uses' => 'AccessTokenController@index']);
        Route::post('profile/access-token', ['as' => 'access_token.store', 'uses' => 'AccessTokenController@store']);
        Route::delete('profile/access-token', ['as' => 'access_token.delete', 'uses' => 'AccessTokenController@delete']);

        Route::get('profile/verified', ['as' => 'profile.verified', 'uses' => 'ProfileController@verified'])
            ->middleware('verified');

        Route::group(['middleware' => 'admin'], function () {
            // Admin - 会员列表 - ok
            Route::resource('user', 'Admin\UserController', ['except' => ['show']]);
            // 系统配置
            Route::get('settings', ['as' => 'settings', 'uses' => 'SettingsController@show']);
            Route::post('settings/save', ['as' => 'settings.save', 'uses' => 'SettingsController@save']);
        });
    });

    Route::group(['prefix' => 'url'], function () {
        // 批量创建页面 - ok
        Route::get('multiple', 'UrlMultipleController@createMultiple')->name('multiple');
        // 批量创建短域名 - ok
        Route::post('multiple', 'UrlMultipleController@storeMultiple')->name('store-multiple');
        Route::post('short', 'UrlController@checkExistingUrl')->name('short')->name('url.short')
            ->middleware('verifycheck');
        // 我的域名 - ok
        Route::get('my', 'UrlController@getMyUrls')->middleware('auth')->name('url.my')
            ->middleware('verifycheck');
        // 公开短域名 - ok
        Route::get('public', 'UrlController@publicUrls')->name('url.public');


        // 管理员查看短链接列表 - ok
        Route::get('list', 'UrlController@showUrlsList')->middleware('admin')->name('url.list');
        // 管理员查看短链接列表的数据加载 - ok
        Route::get('list-load', 'UrlController@loadUrlsList')->middleware('admin')->name('url.list-load');
        Route::get('referers', 'AnalyticController@showReferrersList')->name('url.referers')->middleware('admin');
    });

    // We use "show" in place of "edit", because the "real" show is /{url}
    Route::resource('url', 'UrlController')->except(['edit', 'index'])->middleware(['verifycheck', 'honeypot']);

    // 短链接的详情统计页 - ok
    Route::get('/{url}+', 'AnalyticController@show')->name('stats');
    Route::get('/{url}.svg', 'QRCodeController@svg')->name('qrcode.svg');
    Route::get('/{url}.png', 'QRCodeController@png')->name('qrcode.png');
    // 点击短链接 - ok
    Route::get('/{url}', 'UrlClickController@click')->name('click');
});
