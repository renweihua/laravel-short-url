<?php

namespace App\Modules\ShortUrl\Database\Seeders;

use App\Models\ShortSetting;
use Illuminate\Database\Seeder;

class ShortSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'key' => 'anonymous_urls',
                'value' => 1,
            ],
            [
                'key' => 'private_site',
                'value' => 0,
            ],
            [
                'key' => 'show_guests_latests_urls',
                'value' => 1,
            ],
            [
                'key' => 'hash_ip',
                'value' => 0,
            ],
            [
                'key' => 'anonymize_ip',
                'value' => 0,
            ],
            [
                'key' => 'disable_referers',
                'value' => 0,
            ],
            [
                'key' => 'reservedShortUrls',
                'value' => '[""]',
            ],
            [
                'key' => 'deleted_urls_can_be_recreated',
                'value' => 1,
            ],
            [
                'key' => 'website_name',
                'value' => config('app.name'),
            ],
            [
                'key' => 'website_image',
                'value' => '/images/urlhum.png',
            ],
            [
                'key' => 'website_favicon',
                'value' => '/images/favicon.ico',
            ],
            [
                'key' => 'privacy_policy',
                'value' => '',
            ],
            [
                'key' => 'enable_privacy_policy',
                'value' => 1,
            ],
            [
                'key' => 'terms_of_use',
                'value' => '',
            ],
            [
                'key' => 'enable_terms_of_use',
                'value' => 1,
            ],
            [
                'key' => 'require_user_verify',
                'value' => 1,
            ],
            [
                'key' => 'custom_html',
                'value' => '',
            ],
            [
                'key' => 'min_hash_length',
                'value' => 5,
            ],
        ];
        foreach ($settings as $item){
            ShortSetting::create($item);
        }
    }
}
