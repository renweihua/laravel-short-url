<?php

namespace App\Modules\ShortUrl\Http\Controllers;

use App\Models\Url;
use App\Models\UrlClick;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends ShortUrlController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function dashboard()
    {
        // We initialize the anonymous var to verify later if the user is anonymous or not
        $anonymous = false;

        if (!Auth::check()) {
            $anonymous = true;
        }

        $anonymousUrls = setting('anonymous_urls');

        // We null the referers Widget to enable it just if the user is an admin and has referers enabled
        $referersWidget = null;
        if (!$anonymous && isAdmin() && !setting('disable_referers')) {
            $referersWidget = UrlClick::referersWidget();
        }

        $publicWidget = Url::publicUrlsWidget();
        if (! setting('show_guests_latests_urls') && $anonymous) {
            $publicWidget = null;
        }

        return view('shorturl::dashboard', [
            'publicUrls' => $publicWidget,
            'referers' => $referersWidget,
            'urlsCount' => Url::count(),
            'usersCount' => User::count(),
            'referersCount' => UrlClick::count(DB::raw('DISTINCT referer')),
            'anonymous' => $anonymous,
            'anonymous_urls' => $anonymousUrls,
        ]);
    }
}
