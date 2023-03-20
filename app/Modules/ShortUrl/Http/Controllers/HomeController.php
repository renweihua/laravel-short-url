<?php

namespace App\Modules\ShortUrl\Http\Controllers;

use App\Models\Url;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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
            'referersCount' => rand(0, 10000),
            'anonymous' => $anonymous,
            'anonymous_urls' => $anonymousUrls,
        ]);
    }
}
