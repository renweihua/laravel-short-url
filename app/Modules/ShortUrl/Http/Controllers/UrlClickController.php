<?php

namespace App\Modules\ShortUrl\Http\Controllers;

use App\Library\IpAnonymizer;
use App\Models\Url;
use App\Models\UrlClick;
use App\Modules\ShortUrl\Http\Requests\ShortUrlRequest;
use App\Modules\ShortUrl\Services\UrlService;
use GeoIp2\Database\Reader;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UrlClickController extends ShortUrlController
{
    /**
     * The actual "url" page, redirects the user.
     *
     * @param $url
     * @return RedirectResponse
     */
    public function click($url): RedirectResponse
    {
        $urlService = new UrlService();

        $urlRecord = $result = Url::whereRaw('BINARY `short_url` = ?', [$url])->firstOrFail();
        if ($urlRecord) {
            $externalUrl = $urlService->getLongUrl($result);
        }

        $ip = request()->ip();

        if (setting('disable_referers')) {
            $referer = null;
        } else {
            $referer = request()->server('HTTP_REFERER');
        }

        $hashed = 0;
        $anonymized = 0;

        if (setting('anonymize_ip')) {
            $Anonip = IpAnonymizer::anonymizeIp($ip);
            $anonymized = 1;
            $countries = $this->getCountries($Anonip);
        } else {
            $countries = $this->getCountries($ip);
        }

        if (setting('hash_ip')) {
            $ip = hash('sha1', $ip);
            $hashed = 1;
        }

        $click = 1;
        $real_click = 0;

        if (UrlClick::realClick($urlRecord->id, $ip)) {
            $click = 0;
            $real_click = 1;
        }

        if (! setting('hash_ip') && setting('anonymize_ip')) {
            $click = 1;
            $real_click = 0;
        }

        $data = [
            'url_id' => $urlRecord->id,
            'short_url' => $url,
            'click' => $click,
            'real_click' => $real_click,
            'country' => $countries['countryCode'],
            'country_full' => $countries['countryName'],
            'referer' => $referer ?? null,
            'ip_address' => $ip,
            'ip_hashed' => $hashed,
            'ip_anonymized' => $anonymized,
        ];

        UrlClick::store($data);

        return Redirect::away($externalUrl);
    }

    public function getCountries($ip): array
    {
        // We try to get the IP country using (or not) the anonymized IP
        // If it fails, because GeoLite2 doesn't know the IP country, we
        // will set it to Unknown
        try {
            $reader = new Reader(app_path().'/../database/GeoLite2-Country.mmdb');
            $record = $reader->country($ip);
            $countryCode = $record->country->isoCode;
            $countryName = $record->country->name;

            return compact('countryCode', 'countryName');
        } catch (\Exception $e) {
            $countryCode = 'N/A';
            $countryName = 'Unknown';

            return compact('countryCode', 'countryName');
        }
    }
}
