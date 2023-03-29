<?php

namespace App\Modules\ShortUrl\Database\Seeders;

use App\Models\ShortDeviceTargetsEnum;
use Illuminate\Database\Seeder;

class ShortDeviceTargetEnumDatabaseSeeder extends Seeder
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
            ShortDeviceTargetsEnum::create($item);
        }
    }
}
