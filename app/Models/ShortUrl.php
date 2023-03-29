<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ShortUrl extends Model
{
    /**
     * Create a Short URL based on the given parameters.
     *
     * @param $long_url
     * @param $short_url
     * @param $is_public
     * @param $is_hidden
     *
     * @return ShortUrl
     */
    public static function createShortUrl($long_url, $short_url, $is_public = 1, $is_hidden = 0): ShortUrl
    {
        $user_id = 0;
        if (Auth::check()) {
            $user_id = Auth::id();
        }

        $url = new self;
        $url->long_url = $long_url;
        if ($short_url) $url->short_url = $short_url;
        $url->user_id = $user_id;
        $url->is_public = $is_public;
        $url->is_hidden = $is_hidden;

        // 访问IP与浏览器信息
        $ip_agent = get_client_info();
        $url->created_ip = $ip_agent['ip'] ?? get_ip();
        $url->browser_type = $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'];
        $url->save();

        return $url;
    }

    public static function assignShortUrlToUrl(ShortUrl $url, string $shortUrl): ShortUrl
    {
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
        return self::where('is_public', '=', 1)
            ->select('short_url', 'long_url', 'created_time')
            ->withCount(['clicks as clicks'])
            ->groupBy('short_url', 'long_url', 'created_time')
            ->orderBy('created_time', 'DESC')
            ->paginate(20);
    }

    /**
     * Same as above, but with "limit" instead of "paginate".
     * This is for a widget.
     *
     * @return Collection
     */
    public static function publicUrlsWidget()
    {
        return self::where('is_public', '=', 1)
            ->select(['short_url', 'long_url', 'created_time'])
            ->withCount(['clicks as clicks'])
            ->groupBy('short_url', 'long_url', 'created_time')
            ->orderBy('created_time', 'DESC')
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

        $user_id = Auth::id();

        return self::where('user_id', $user_id)->paginate(30);
    }

    /**
     * ShortUrl Eloquent hasMany relationship with ViewUrl.
     *
     * @return HasMany
     */
    public function clicks()
    {
        return $this->hasMany(ShortUrlClick::class, 'url_id', 'id');
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
        return $this->hasMany(ShortDeviceTarget::class, 'short_url_id', 'id');
    }
}
