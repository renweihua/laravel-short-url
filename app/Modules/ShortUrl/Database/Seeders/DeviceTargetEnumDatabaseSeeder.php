<?php

namespace App\Modules\ShortUrl\Database\Seeders;

use App\Models\DeviceTargetsEnum;
use Illuminate\Database\Seeder;

class DeviceTargetEnumDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'windows',
                'display_name' => 'Windows'
            ],
            [
                'name' => 'macos',
                'display_name' => 'Mac OS'
            ],
            [
                'name' => 'android',
                'display_name' => 'Android'
            ],
            [
                'name' => 'ios',
                'display_name' => 'iOS'
            ]
        ];
        foreach ($data as $item){
            DeviceTargetsEnum::create($item);
        }
    }
}
