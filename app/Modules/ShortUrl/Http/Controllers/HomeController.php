<?php

namespace App\Modules\ShortUrl\Http\Controllers;

use App\Models\Url;
use App\Models\UrlClick;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class HomeController extends ShortUrlController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function dashboard()
    {
        $referersWidget = [];
        $anonymous = '';
        $anonymousUrls = '';
        return view('shorturl::dashboard', [
            'publicUrls' => Url::publicUrlsWidget(),
            'referers' => $referersWidget,
            'urlsCount' => Url::count(),
            'usersCount' => User::count(),
            'referersCount' => UrlClick::count(DB::raw('DISTINCT referer')),
            'anonymous' => $anonymous,
            'anonymous_urls' => $anonymousUrls,
        ]);
    }
}
