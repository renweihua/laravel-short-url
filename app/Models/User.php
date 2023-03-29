<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Check if the current user is an Admin.
     *
     * @return bool
     */
    public static function isAdmin()
    {
        if (! auth()->user()) {
            return false;
        }

        if (auth()->user()->user_id == 1) {
            return true;
        }

        return false;
    }

    public function urls()
    {
        return $this->hasMany(Url::class, 'user_id', 'user_id');
    }

    public function userInfo()
    {
        return $this->hasOne(UserInfo::class, 'user_id', 'user_id');
    }

    public static function getDetailById($user_id)
    {
        $cache_key = 'user:' . $user_id;
        $user = Cache::get($cache_key);
        if (!$user){
            $user = self::with('userInfo')->find($user_id);
            Cache::put($cache_key, $user, Carbon::now()->addDays(1));
        }
        return $user;
    }
}
