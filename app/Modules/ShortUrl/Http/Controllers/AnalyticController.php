<?php

namespace App\Modules\ShortUrl\Http\Controllers;

use App\Models\Url;
use App\Models\User;
use App\Modules\ShortUrl\Services\AnalyticService;
use App\Modules\ShortUrl\Services\UrlService;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class AnalyticController extends ShortUrlController
{

    /**
     * @var UrlService
     */
    protected $url;

    protected $analytics;

    /**
     * AnalyticsController constructor.
     *
     * @param UrlService $urlService
     * @param AnalyticService $analytics
     */
    public function __construct(UrlService $urlService, AnalyticService $analytics)
    {
        $this->url = $urlService;
        $this->analytics = $analytics;
    }

    /**
     * Show the URL analytics page to user.
     *
     * @param $url
     * @return Factory|View
     */
    public function show($url)
    {
        $urlWithRelations = Url::withCount([
            'clicks',
            'clicks as real_clicks_count' => function ($query) {
                $query->where('real_click', 1);
            },
            'clicks as today_clicks_count' => function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subDay());
            },
        ])->whereRaw('BINARY `short_url` = ?',  [$url])->firstOrFail();

        if ($urlWithRelations->hide_stats && ! $this->url->OwnerOrAdmin($url)) {
            abort(403);
        }

        $data = [
            'url' => $url,
            'clicks' => $urlWithRelations->clicks_count,
            'realClicks' => $urlWithRelations->real_clicks_count,
            'todayClicks' => $urlWithRelations->today_clicks_count,
            'countriesClicks' => $this->analytics::getCountriesClicks($url),
            'countriesColor' =>  $this->analytics::getCountriesColor($this->analytics::getCountriesClicks($url)),
            'latestClicks' => $this->analytics::getLatestClicks($url),
            'referers' =>  $this->analytics::getUrlReferers($url),
            'creationDate' => Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', $urlWithRelations->created_time))->diffForHumans(),
            'isOwnerOrAdmin' => $this->url->OwnerOrAdmin($url),
        ];

        return view('shorturl::analytics.urlAnalytics')->with($data);
    }
}