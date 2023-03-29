<?php

/*
 * UrlHum (https://urlhum.com)
 *
 * @link      https://github.com/urlhum/UrlHum
 * @copyright Copyright (c) 2019 Christian la Forgia
 * @license   https://github.com/urlhum/UrlHum/blob/master/LICENSE.md (MIT License)
 */

namespace App\Modules\ShortUrl\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

// 视图共享登录会员信息：控制层初始化无法获取`Auth::id`
class ViewShareLoginUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 登录会员信息
        $login_user_id = Auth::id();
        if ($login_user_id){
            View::share('login_user', User::getDetailById($login_user_id));
        }else{
            View::share('login_user', []);
        }

        return $next($request);
    }
}
