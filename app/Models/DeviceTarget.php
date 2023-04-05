<?php

namespace App\Models;

class DeviceTarget extends Model
{
    public function enum()
    {
        return $this->hasOne(DeviceTargetsEnum::class, 'id', 'device');
    }
}
