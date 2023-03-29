<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use JsonException;

class ShortSetting extends Model
{
    public static function getSettingCacheKey()
    {
        return 'system:settings';
    }

    /**
     * Get all settings.
     *
     * @return mixed
     * @throws JsonException
     */
    public static function getAllSettings($force = false)
    {
        $cache_key = self::getSettingCacheKey();
        $settings = Cache::get($cache_key);
        if (!$settings || $force){
            $settings = ShortSetting::pluck('value', 'key');

            $reserved = json_decode($settings->get('reservedShortUrls'), true, 512, JSON_THROW_ON_ERROR);
            // Check if there are actually any reserved Short URLs
            // In case there aren't, we don't treat $reserved like an array
            if (is_array($reserved)) {
                $reserved = implode(PHP_EOL, $reserved);
            }
            $settings->put('reservedShortUrls', $reserved);

            Cache::put($cache_key, $settings, Carbon::now()->addDays(7));
        }
        return $settings;
    }

    /**
     * Load the reserved URLs and json_decode them.
     *
     * @return mixed
     * @throws JsonException
     */
    public static function getReservedUrls()
    {
        $settings = setting('reservedShortUrls', '');

        return $settings;
    }

    /**
     * Save images uploaded from settings page in the public/images folder.
     *
     * @param $image
     * @return string
     */
    public static function saveImage($image): string
    {
        $imageName = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName);

        return '/images/' . $imageName;
    }

    public static function batchSave($data)
    {
        // 获取现有配置，对比需要更新的配置
        $original_configs = self::getAllSettings();
        foreach ($data as $key => $value){
            if ($original_configs->get($key) == $value){
                continue;
            }
            self::where('key', $key)->update(['value' => $value]);
        }
        // 清除缓存
        Cache::forget(self::getSettingCacheKey());
    }
}
