<?php

namespace App\Models;

class ShortDeviceTarget extends Model
{
    public function enum()
    {
        return $this->hasOne(ShortDeviceTargetsEnum::class, 'id', 'device');
    }
}
