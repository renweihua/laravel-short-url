<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UrlClick extends Model
{
    /**
     * Store a new Click in database.
     *
     * @param $data
     */
    public static function store($data)
    {
        $clickUrl = new self;
        $clickUrl->fill($data);
        $clickUrl->save();
    }

    /**
     * Check if the click is actually real or not, based on the IP and datetime.
     *
     * @param $short_url
     * @param $ip_address
     * @return bool
     */
    public static function realClick($short_url, $ip_address)
    {
        $click = self::whereRaw('BINARY `short_url` = ?', [$short_url])
            ->where('ip_address', $ip_address)
            ->where('created_time', '>=', Carbon::now()->subDay())
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('ip_hashed', 1)
                        ->where('ip_anonymized', 1);
                })->orWhere(function ($query) {
                    $query->where('ip_hashed', 1)
                        ->where('ip_anonymized', 0);
                })->orWhere(function ($query) {
                    $query->where('ip_hashed', 0)
                        ->where('ip_anonymized', 0);
                });
            })
            ->limit(1)
            ->orderBy('created_time', 'desc')
            ->get();

        return $click->isNotEmpty() ? false : true;
    }

    /**
     * Get the referers list.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getReferersList()
    {
        $urls = self::select('referer', \DB::raw('sum(click+real_click) as clicks'), \DB::raw('sum(real_click) as real_clicks'))
            ->groupBy('referer')
            ->orderBy('real_clicks', 'DESC')
            ->paginate(40);

        return $urls;
    }

    /**
     * Same as above, but with "limit" instead of "paginate".
     * This is for a widget.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function referersWidget()
    {
        $urls = self::select('referer', \DB::raw('sum(click+real_click) as clicks'), \DB::raw('sum(real_click) as real_clicks'))
            ->groupBy('referer')
            ->orderBy('real_clicks', 'DESC')
            ->limit(9)
            ->get();

        return $urls;
    }

    /**
     * When a Short URL is deleted, we delete its analytical data too.
     *
     * @param $url
     */
    public static function deleteUrlsClicks($url)
    {
        self::whereRaw('BINARY `short_url` = ?', [$url])->delete();
    }

    /**
     * Eloquent relationship for URL.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function url()
    {
        return $this->belongsTo('App\Url', 'short_url', 'short_url');
    }
}
