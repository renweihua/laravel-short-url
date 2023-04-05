<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

class Friendlink extends Model
{
    // 连接User库
    protected $connection = 'user_mysql';

    protected $primaryKey = 'link_id';

    /**
     * 前端：获取友情链接
     *
     * @param  bool  $force_update 是否强制更新缓存
     *
     * @return mixed
     */
    public static function getFriendlinksByWeb(bool $force_update = false)
    {
        $cache_key = 'friendlinks';
        // 强制更新缓存
        if ( $force_update ) {
            // 删除缓存key
            Cache::forget($cache_key);
        }
        $friendlinks = Cache::remember($cache_key, now()->addDays(7), function() {
            return self::where('is_check', 1)->orderBy('link_sort', 'ASC')->get();
        });
        return $friendlinks;
    }
}
