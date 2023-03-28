<?php

namespace App\Modules\ShortUrl\Http\Controllers;

use App\Models\Url;
use App\Models\UrlClick;
use App\Modules\ShortUrl\Http\Requests\ShortUrlRequest;
use App\Modules\ShortUrl\Services\UrlService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

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

    /**
     * Show the user its own short URLs.
     *
     * @return Factory|View
     */
    public function getMyUrls()
    {
        $urls = Url::getMyUrls();

        return view('shorturl::url.my')->with('urls', $urls);
    }



    /**
     * Show the "edit" form of the URL.
     * This method actually shows the URL edit page. It is not actually "@show" URL. The URL show is in clickUrl@view.
     *
     * @param $url
     * @return ResponseFactory|Factory|Response|View
     */
    public function show($url)
    {
        $url = Url::with('user:id,name,email')->whereRaw('BINARY `short_url` = ?', [$url])->firstOrFail();

        if (! $this->url->OwnerOrAdmin($url) ) {
            abort(403);
        }

        $targets = $this->url->getTargets($url);

        $data['url'] = $url;

        $data['targets'] = $targets;

        return view('shorturl::url.edit')->with('data', $data);
    }

    /**
     * Update the URL on the user request.
     *
     * @param $url
     * @param ShortUrlRequest $request
     *
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function update($url, ShortUrlRequest $request)
    {
        $url = Url::whereRaw('BINARY `short_url` = ?', [$url])->firstOrFail();

        if (! $this->url->OwnerOrAdmin($url)) {
            return response('Forbidden', 403);
        }

        $data = $request->validated();

        $url->is_public = $data['privateUrl'];
        $url->is_hidden = $data['hideUrlStats'];
        $url->long_url = $data['url'];
        $url->update();

        return Redirect::back()
            ->with('success', 'Short URL updated successfully.');
    }

    /**
     * Delete a Short URL on user request.
     *
     * @param $url
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy($short_url)
    {
        $url = Url::whereRaw('BINARY `short_url` = ?',  [$short_url])->firstOrFail();

        if (! $this->url->OwnerOrAdmin($url)) {
            return response('Forbidden', 403);
        }

        UrlClick::deleteUrlsClicks($url);

        $url->deviceTargets()->delete();
        $url->delete();

        return Redirect::route('url.my')->with(['success' => 'Short url "'.$url->short_url.'" deleted successfully. Its Analytics data has been deleted too.']);
    }

    /**
     * Response to an AJAX request by the custom Short URL form.
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function checkExistingUrl(Request $request)
    {
        if ($this->url->isUrlReserved($request->input) ||
            Url::whereRaw('BINARY `short_url` = ?', [$request->input])->exists() ||
            (! setting('deleted_urls_can_be_recreated') && $this->url->isUrlAlreadyDeleted($request->input)) || $this->url->isShortUrlProtected($request->input)) {
            return response('Custom URL already existing', 409);
        }

        return response('ok');
    }
}
