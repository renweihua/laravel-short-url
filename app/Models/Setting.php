<?php

namespace App\Models;

class Setting extends Model
{
    /**
     * Get all settings.
     *
     * @return mixed
     * @throws JsonException
     */
    public static function getAllSettings()
    {
        $settings = Setting::pluck('value', 'key');

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
        $settings = setting('reservedShortUrls', []);

        return json_decode($settings, true, 512, JSON_THROW_ON_ERROR);
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
}
