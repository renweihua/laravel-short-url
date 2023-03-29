<?php

namespace App\Modules\ShortUrl\Http\Controllers;

use App\Models\ShortUrl;
use App\Modules\ShortUrl\Services\AnalyticService;
use App\Modules\ShortUrl\Services\UrlService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
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
        parent::__construct();

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
        $urlWithRelations = ShortUrl::withCount([
            'clicks',
            'clicks as real_clicks_count' => function ($query) {
                $query->where('real_click', 1);
            },
            'clicks as today_clicks_count' => function ($query) {
                $query->where('created_time', '>=', Carbon::now()->subDay());
            },
        ])->whereRaw('BINARY `short_url` = ?',  [$url])->firstOrFail();

        if ($urlWithRelations->is_hidden && ! $this->url->OwnerOrAdmin($urlWithRelations)) {
            abort(403);
        }

        $data = [
            'url' => $url,
            'clicks' => $urlWithRelations->clicks_count,
            'realClicks' => $urlWithRelations->real_clicks_count,
            'todayClicks' => $urlWithRelations->today_clicks_count,
            'countriesClicks' => $this->analytics::getCountriesClicks($urlWithRelations->id),
            'countriesColor' =>  $this->analytics::getCountriesColor($this->analytics::getCountriesClicks($urlWithRelations->id)),
            'latestClicks' => $this->analytics::getLatestClicks($urlWithRelations->id),
            'referers' =>  $this->analytics::getUrlReferers($urlWithRelations->id),
            'creationDate' => formatting_timestamp($urlWithRelations->created_time),
            'isOwnerOrAdmin' => $this->url->OwnerOrAdmin($urlWithRelations),
        ];

        return view('shorturl::analytics.urlAnalytics')->with($data);
    }
}
