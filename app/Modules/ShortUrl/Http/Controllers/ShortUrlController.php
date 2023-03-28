<?php

namespace App\Modules\ShortUrl\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class ShortUrlController extends Controller
{
    public function __construct()
    {
        if (Cache::has('language')){
            App::setLocale(Cache::get('language'));
        }

        // 为所有视图共享数据
        View::share('languages', [
            '中文' => 'zh_CN',
            'English' => 'es_ES',
        ]);
    }
}
