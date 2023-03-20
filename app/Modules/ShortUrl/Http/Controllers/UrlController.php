<?php

namespace App\Modules\ShortUrl\Http\Controllers;

use App\Models\Url;
use App\Modules\ShortUrl\Http\Requests\ShortUrlRequest;
use App\Modules\ShortUrl\Services\UrlService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UrlController extends ShortUrlController
{
    /**
     * @var UrlService
     */
    protected $url;
    protected $deviceDetection;

    /**
     * UrlController constructor.
     * @param UrlService $urlService
     */
    public function __construct(UrlService $urlService)
    {
        $this->middleware('throttle:30', ['only' => ['store', 'update', 'checkExistingUrl']]);

        $this->url = $urlService;
    }
    /**
     * Store the data the user sent to create the Short URL.
     *
     * @param ShortUrlRequest $request
     * @return RedirectResponse
     */
    public function store(ShortUrlRequest $request)
    {
        $data = $request->validated();
        $siteUrl = request()->getHttpHost();

        // If user is not logged in, he can't set private statistics,
        // because otherwise they will not be available to anybody else but admin
        if (! Auth::check()) {
            $data['hideUrlStats'] = 0;
        }

        if ($this->url->customUrlExisting($data['customUrl'])) {
            return Redirect::route('home')
                ->with('existingCustom', $data['customUrl']);
        }

        $existing = $this->url->checkExistingLongUrl($data['url']);

        if ($existing !== null) {
            return Redirect::route('home')
                ->with('existing', $existing)
                ->with('siteUrl', $siteUrl);
        }

        $customUrl = '';
        if (!empty($data['customUrl'])) {
            $customUrl = $data['customUrl'];
        }

        try {
            $url = $this->url->shortenUrl($data['url'], $customUrl, $data['privateUrl'], $data['hideUrlStats']);
        } catch (\Exception $ex) {
            return Redirect::route('home')
                ->with('error', 'Error. Please try again.');
        }

        $short = $url->short_url;

        $this->url->assignDeviceTargetUrl($data, $url->id);

        return Redirect::route('home')
            ->with('success', $short)
            ->with('siteUrl', $siteUrl);
    }

    /**
     * Load the public URLs list to show.
     *
     * @return Factory|View
     */
    public function publicUrls()
    {
        if (! setting('show_guests_latests_urls') && ! isAdmin()) {
            abort(404);
        }

        return view('shorturl::url.public')->with('urls', Url::getLatestPublicUrls());
    }
}
