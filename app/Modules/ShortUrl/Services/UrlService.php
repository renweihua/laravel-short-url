<?php

namespace App\Modules\ShortUrl\Services;

use App\Models\ShortDeviceTarget;
use App\Models\ShortDeviceTargetsEnum;
use App\Models\ShortSetting;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Hashids\Hashids;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class UrlService
{
    /**
     * Actually creates a short URL if there is no custom URL. Otherwise, use the custom.
     *
     * @param $long_url
     * @param $short_url
     * @param $privateUrl
     * @param $hideUrlStats
     *
     * @return ShortUrl
     * @throws RuntimeException
     */
    public function shortenUrl($long_url, $short_url, $privateUrl, $hideUrlStats): ShortUrl
    {
        $lock_key = 'lock:create:short:url:' . md5($long_url . $short_url);
        $lock = Cache::lock($lock_key, 60);

        $url = ShortUrl::createShortUrl($long_url, $short_url, $privateUrl, $hideUrlStats);
        if (!$short_url) {
            $short_url = $this->generateShortUrl($url);
            if (!$short_url) {
                throw new RuntimeException();
            }
        }

        $url = ShortUrl::assignShortUrlToUrl($url, $short_url);

        Cache::restoreLock($lock_key, $lock->owner());

        return $url;
    }

    /**
     * Generate an unique short URL using hashids. Salt is the APP_KEY, which is always unique.
     *
     * @param ShortUrl $url
     *
     * @return string
     */
    public function generateShortUrl($url): string
    {
        $checksQuantity = 0;
        do {
            if ($checksQuantity > 5) {
                return '';
            }
            $hashLength = setting('min_hash_length') ?? 4;
            $hashids = new Hashids(env('APP_KEY'), $hashLength);
            $encoded = $hashids->encode($url->id);
            $alreadyGenerated = false;
            if ($this->isUrlReserved($encoded) || ShortUrl::whereRaw('BINARY `short_url` = ?', [$encoded])->exists()) {
                $alreadyGenerated = true;
                $checksQuantity++;
            }
        } while ($alreadyGenerated);

        return $encoded;
    }

    /**
     * Check if is possible to use the Custom URL or not.
     *
     * @param $url
     * @return bool
     */
    public function customUrlExisting($url)
    {
        if ($this->checkExistingCustomUrl($url) ||
            $this->isShortUrlProtected($url) ||
            $this->isUrlReserved($url) ||
            (! setting('deleted_urls_can_be_recreated'))) {
            return true;
        }

        return false;
    }

    /**
     * Check if the logged in user is the URL Owner or an Admin.
     *
     * @param ShortUrl $url
     *
     * @return bool
     */
    public function OwnerOrAdmin(ShortUrl $url)
    {
        return User::isAdmin() || $this->isOwner($url);
    }

    /**
     * Check if the logged in user is the Short URL owner.
     *
     * @param ShortUrl $url
     *
     * @return bool
     */
    public function isOwner(ShortUrl $url)
    {
        if (! Auth::check()) {
            return false;
        }

        return $url->user_id === Auth::id();
    }

    /**
     * Check if the Custom URL already exists.
     *
     * @param $custom_url
     * @return bool
     */
    public function checkExistingCustomUrl($custom_url): bool
    {
        // Check if custom url has been typed by user
        if (is_null($custom_url)) {
            return false;
        }

        return ShortUrl::whereRaw('BINARY `short_url` = ?', [$custom_url])->exists() || $this->isUrlReserved($custom_url);
    }

    /**
     * Check if the long URL exists on database. If so, return the short URL.
     *
     * @param $long_url
     * @return mixed|null
     */
    public function checkExistingLongUrl($long_url)
    {
        $long_url_check = ShortUrl::where('long_url', $long_url)->first();

        if ($long_url_check === null) {
            return null;
        }

        return $long_url_check['short_url'];
    }

    /**
     * Check if Short URL is protected / cannot be created
     * because it is a path.
     *
     * @param ShortUrl
     *
     * @return bool
     */
    public function isShortUrlProtected($url): bool
    {
        $routes = array_map(
            static function (Route $route) {
                return $route->uri;
            }, (array) \Route::getRoutes()->getIterator()
        );

        return in_array($url, $routes, true);
    }

    /**
     * Check if the URL is reserved, based on the system setting.
     *
     * @param $url
     * @return bool
     */
    public function isUrlReserved($url): bool
    {
        $reservedUrls = ShortSetting::getReservedUrls();
        // Check if there are any reserved URLs or if the custom URL isn't set
        if (!is_array($reservedUrls) || $url === null) {
            return false;
        }

        return in_array($url, $reservedUrls, true);
    }

    /*
     * Let's assign at every URL the value sent by the form
     */
    /**
     * @param $request
     * @param $url_id
     */
    public function assignDeviceTargetUrl($request, $url_id): void
    {
        $data = [];

        $enums = ShortDeviceTargetsEnum::all();

        $time = time();
        foreach ($enums as $device) {
            if (isset($request[$device->name]) && $request[$device->name] !== null) {
                $data[] = [
                    'url_id' => $url_id,
                    'device_id' => $device->id,
                    'target_url' => $request[$device->name],
                    'created_time' => $time,
                    'updated_time' => $time
                ];
            }
        }

        ShortDeviceTarget::insert($data);
    }

    /**
     * @param $url
     * @return Collection
     */
    public function getTargets(ShortUrl $url)
    {
        return ShortDeviceTargetsEnum::leftJoin('short_device_targets', function ($join) use ($url){
                $join->on('short_device_targets.device_id', '=', 'short_device_targets_enums.id');
                $join->where('short_device_targets.target_url', '=', $url->id);
            })
            ->get();
    }


    /**
     * @param $short_url
     * @return bool|string
     */
    public function getLongUrl(ShortUrl $short_url)
    {
        $deviceDetection = new DeviceDetection();

        $platformId = $deviceDetection->getPlatform();

        $targets = $this->getTargets($short_url);

        return $targets->where('device', $platformId)->first()->target_url ?? $short_url->long_url;
    }
}
