<?php

namespace App\Modules\ShortUrl\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class ShortUrlController extends Controller
{
    protected $languages = [
        '中文' => 'zh_CN',
        'English' => 'es_ES',
    ];

    public function __construct()
    {
        $cache_key = $this->getLanaguageCacheKey();
        if (Cache::has($cache_key)){
            App::setLocale(Cache::get($cache_key));
        }

        // 为所有视图共享数据
        View::share('languages', $this->languages);
    }

    protected function getLanaguageCacheKey()
    {
        $key = 'language';
        if (Auth::check()){
            $key .= ':user_id:' . Auth::id();
        }else{
            $key .= ':ip:' . get_ip();
        }
        return $key;
    }

    protected function setLanguageByUser($locale)
    {
        Cache::put($this->getLanaguageCacheKey(), $locale, Carbon::now()->addDays(30));
    }
}
