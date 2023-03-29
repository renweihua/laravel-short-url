<?php

namespace App\Modules\ShortUrl\Http\Controllers;

use App\Models\ShortSetting;
use App\Modules\ShortUrl\Http\Requests\SettingRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class SettingController extends ShortUrlController
{
    /**
     * Show the settings page to users.
     *
     * @return Factory|View
     * @throws \JsonException
     */
    public function show()
    {
        $settings = ShortSetting::getAllSettings();

        return view('shorturl::settings')->with('settings', $settings);
    }

    /**
     * Save the settings.
     *
     * @param SettingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(SettingRequest $request)
    {
        $data = $request->validated();

        // We convert reservedShortUrls new lines to array and json-ize the array to save in Database
        $data['reservedShortUrls'] = json_encode(explode(PHP_EOL, $data['reservedShortUrls']));

        $imagesVars = ['website_image', 'website_favicon'];
        foreach ($imagesVars as &$var) {
            if ($request->exists($var)) {
                $data[$var] = ShortSetting::saveImage($data[$var]);
            }
        }

        $textareaVars = ['privacy_policy', 'terms_of_use', 'custom_html'];
        foreach ($textareaVars as &$var) {
            if ($data[$var] == null) {
                $data[$var] = ' ';
            }
        }

        ShortSetting::batchSave($data);

        return redirect()->back()->with('success', trans('settings.success'));
    }
}
