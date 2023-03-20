<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Url extends Model
{
    /**
     * Create a Short URL based on the given parameters.
     *
     * @param $long_url
     * @param $short_url
     * @param $is_public
     * @param $is_hidden
     * @return int
     */
    public static function createShortUrl($long_url, $short_url, $is_public = 1, $is_hidden = 0): int
    {
        $user_id = 0;
        if (Auth::check()) {
            $user_id = Auth::user()->id;
        }

        $url = new self;
        $url->long_url = $long_url;
        $url->short_url = $short_url;
        $url->user_id = $user_id;
        $url->is_public = $is_public;
        $url->is_hidden = $is_hidden;
        $url->save();

        return (int) DB::getPdo()->lastInsertId();
    }

    public static function assignShortUrlToUrl(int $urlId, string $shortUrl): Url
    {
        $url = self::find($urlId);
        $url->short_url = $shortUrl;
        $url->save();

        return $url;
    }

    /**
     * Retrieve the latest URLs that are public.
     *
     * @return LengthAwarePaginator
     */
    public static function getLatestPublicUrls(): LengthAwarePaginator
    {
        return DB::table('urls')
            ->select('urls.short_url', 'urls.long_url', \DB::raw('count(clicks.short_url) as clicks'), 'urls.created_at')
            ->leftJoin('clicks', 'urls.short_url', '=', 'clicks.short_url')
            ->groupBy('urls.short_url', 'urls.long_url', 'urls.created_at')
            ->orderBy('urls.created_at', 'DESC')
            ->where('private', '=', 0)
            ->paginate('20');
    }

    /**
     * Same as above, but with "limit" instead of "paginate".
     * This is for a widget.
     *
     * @return Collection
     */
    public static function publicUrlsWidget()
    {
        return DB::table('urls')
            ->select('urls.short_url', 'urls.long_url', \DB::raw('count(clicks.short_url) as clicks'), 'urls.created_time')
            ->leftJoin('clicks', 'urls.short_url', '=', 'clicks.short_url')
            ->groupBy('urls.short_url', 'urls.long_url', 'urls.created_time')
            ->orderBy('urls.created_time', 'DESC')
            ->where('is_public', '=', 1)
            ->limit(8)
            ->get();
    }

    /**
     * Load the URLs of the currently logged in user.
     *
     * @return LengthAwarePaginator
     */
    public static function getMyUrls()
    {
        if (! Auth::check()) {
            abort(404);
        }

        $user_id = Auth::user()->id;

        return self::where('user_id', $user_id)->paginate(30);
    }

    /**
     * Url Eloquent hasMany relationship with ViewUrl.
     *
     * @return HasMany
     */
    public function clicks()
    {
        return $this->hasMany(UrlClick::class, 'short_url', 'short_url');
    }

    /**
     * Eloquent relationship, which tells the user of the Short URL.
     * If the user doesn't exist, email will be "Anonymous".
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->withDefault(function ($user) {
                $user->email = 'Anonymous';
            });
    }

    /**
     * @return HasMany
     */
    public function deviceTargets(): HasMany
    {
        return $this->hasMany(DeviceTarget::class, 'short_url_id', 'id');
    }
}
